
import { Juego } from '../juego'
import { Player } from './player'
import { Mazo } from './mazo'
import { Carta } from './carta'
import { BlackJackComponent } from "../../componentes/black-jack/black-jack.component";
import { setInterval } from 'timers';
import { JuegoServiceService } from "../../servicios/juego-service.service";
import { ArchivosJuegosServiceService } from "../../servicios/archivos-juegos-service.service";

export class BlackJack extends Juego {
    mazo: Mazo;
    playerCount: number;
    jugadores: Array<Player>;
    manoTerminada: boolean;
    sumaCrupier: number;
    cardDelay: number = 250; // tiempo en milisegundos (ms)
    private cardIteration: number = 0;
    
    constructor(public servicioJuego: JuegoServiceService) {
        super("BlackJack", 'Black Jack');
        this.mazo = new Mazo();
        this.playerCount = 1;
        this.sumaCrupier = 0;
        this.manoTerminada = true;
        this.jugadores = new Array<Player>();
        for (let index = 0; index <= this.playerCount; index++) {
            this.jugadores.push(new Player(index==0?'Crupier':undefined));
        }
    }

    public guardarJugada(): boolean { // polimorfismo de la superclase Juego...
        
        this.datos = JSON.stringify({
            gano: (this.jugadores[1].puntaje>this.jugadores[0].puntaje),
            puntaje: this.jugadores[1].puntaje,
            ganadas: this.jugadores[1].ganadas,
            empates: this.jugadores[1].empatadas,
            perdidas: this.jugadores[1].perdidas
        });
        
        this.servicioJuego.guardarPartida('guardarjugada', this);
        console.info('Jugada guardada.');
        return true;
    }

    public verificar(): number { // polimorfismo, definido en super clase Juego...
        let res: number = this.checkAgainstCrupier(this.jugadores[1]);
        this.cardIteration = 0;
        let msg: string;
        if (res < 0) {
            this.jugadores[0].ganadas++;
            this.jugadores[1].perdidas++;
            this.jugadores[0].puntaje += 10;
            if(res == -1) {
                document.getElementById('lblJugador').style.color = 'red';
                msg = 'Ganó el crupier, el jugador se pasó';
            } else if (res == -2) {
                msg = 'Ganó el crupier, obtuvo Black Jack';
                this.jugadores[0].puntaje += 10;
            } else {
                msg = 'Ganó el crupier, obtuvo la jugada más alta';
            }
        } else if (res > 0) {
            this.jugadores[1].ganadas++;
            this.jugadores[0].perdidas++;
            this.jugadores[1].puntaje += 10;
            if(res == 1) {
                document.getElementById('lblCrupier').style.color = 'red';
                msg = 'Ganó el jugador, el crupier se pasó';
            } else if (res == 2) {
                msg = 'Ganó el jugador, obtuvo Black Jack';
                this.jugadores[1].puntaje += 10;
            } else {
                msg = 'Ganó el jugador, obtuvo la jugada más alta';
            }
        } else {
            msg = 'Empate';
            this.jugadores[0].empatadas++;
            this.jugadores[1].empatadas++;
        }

        this.Mensaje(msg);
        this.manoTerminada = true;
        
        return res;
    }

    public Mensaje(msg: string) {
        document.getElementById('lblMensaje').textContent = msg;
        var padre = document.getElementById('lblMensaje').parentElement.parentElement.parentElement;
        var hijo = document.getElementById('lblMensaje').parentElement.parentElement;
        var newone = padre.cloneNode(true);
        hijo.parentElement.replaceChild(newone, hijo);
        
        if(msg != '') console.log(' => '+msg+'.');
    }

    private checkAgainstCrupier(player: Player): number {
        let crupier: Player = this.jugadores[0];
        let tmp: number = 0;

        if (player.suma > 21) { // si el jugador se pasa, automaticamente gana el crupier...
            tmp = -1;  // el jugador perdio por sobrepaso
        } else if (crupier.suma > 21) { // si el crupier se pasa gana el jugador
            tmp = 1;  // el crupier perdio por sobrepaso
        } else if (this.isBlackJack(crupier) && !this.isBlackJack(player)) {
            tmp = -2;  // el crupier gano por black jack
        } else if (!this.isBlackJack(crupier) && this.isBlackJack(player)) {
            tmp = 2;  // el jugador gano por black jack
        } else if (crupier.suma > player.suma) {
            tmp = -3;  // el crupier gano por jugada mas alta
        } else if (crupier.suma < player.suma) {
            tmp = 3;  // el jugador gano por jugada mas alta
        } else {
            tmp = 0; // empate
        }

        return tmp;
    }

    private playCrupier() {
        this.cardIteration = 1;
        let crupier: Player = this.jugadores[0];
        let carta: Carta = this.jugadores[0].cartas[1];
        carta.Flip();
        //document.getElementById(carta.numero+'_'+carta.tipo).setAttribute('src', carta.rutaImagen);
        while (crupier.suma < 17 && this.mazo.cartasRestantes > 0) {
            this.Pedir(0); // indice cero del crupier
        }
        this.sumaCrupier = crupier.suma;
    }
 
    private isBlackJack(player: Player): boolean {
        return player.suma == 21 && player.cartas.length == 2;
    }

    public Pedir(index: number) {
        if(index==1) this.cardIteration = 0;
        let carta: Carta;
        carta = this.mazo.nuevaCarta(false);
        if(carta===null) {
            this.finalizarRonda();
            return;
        }
        this.sacarCarta(carta, index); // indice 1 temporal, jugador 1
        this.jugadores[index].recibirCarta(carta);
        if (this.jugadores[index].suma > 21) {
            if(index!=0) this.verificar();
        }
    }

    private finalizarRonda() {
        this.cardIteration = 0;
        this.manoTerminada = true;
        this.guardarJugada();
        this.finalizarMano();
        this.mazo.reiniciar();
        this.Mensaje('Mazo sin cartas, mezclando...');
        for (let index = 0; index <= this.playerCount; index++) {
            this.jugadores[index].puntaje = 0;
        }
    }

    public Repartir() {
        let carta: Carta;
        let i: number;
        let mano: number;
        
        this.manoTerminada = false;
        this.finalizarMano();
        this.sumaCrupier = 0;
        
        for (mano = 0; mano < 2; mano++) {
            for (i = this.playerCount; i >= 0; i--) {
                carta = this.mazo.nuevaCarta(mano==1&&i==0?true:false);
                if(carta===null) {
                    this.finalizarRonda();
                    return;
                }
                this.sacarCarta(carta, i);
                this.jugadores[i].recibirCarta(carta);
            }
        }
    }

    public Plantarse() {
        this.playCrupier();
        this.verificar();
    }

    public finalizarMano() {
        let board: HTMLElement;
        this.Mensaje('');

        for (let i = 0; i <= this.playerCount; i++) {
            board = document.getElementById('playboard-'+i);
            while (board.firstChild) {
                board.removeChild(board.firstChild);
            }
        }
        document.getElementById('lblCrupier').style.color = 'white';
        document.getElementById('lblJugador').style.color = 'white';
        for (let i = 0; i <= this.playerCount; i++) {
            this.jugadores[i].nuevaMano();
        }

    }

    public Reset() { // only for testing... or when the deck ends
        let board: HTMLElement;
        for (let i = 0; i <= this.playerCount; i++) {
            board = document.getElementById('playboard-'+i);
            while (board.firstChild) {
                board.removeChild(board.firstChild);
            }
        }

        this.mazo.reiniciar();
        /*document.getElementById('lblCrupier').style.color = 'white';
        document.getElementById('lblJugador').style.color = 'white';
        this.jugadores = new Array<Player>();
        for (let index = 0; index <= this.playerCount; index++) {
            this.jugadores.push(new Player());
        }*/
    }

    private sacarCarta(carta: Carta, playerIndex: number) {
        let card = document.createElement("img");
        let board = document.getElementById('playboard-'+playerIndex);
        let container = document.createElement('div');
        
        card.classList.add('card');
        container.classList.add('floating-box');
        container.style.cssText = 
            'float: left;'+
            'width: 100px;'+ 
            'height: 140px;'+ 
            'animation: reparto 0.25s cubic-bezier(0.250, 0.460, 0.450, 0.940) both;'+ 
            'display: inline-block;'+
            'animation-duration: '+this.cardDelay+'ms;'+
            'animation-delay: '+this.cardDelay*this.cardIteration+'ms;';
            
            // margin: 1%; border: 1px solid rgb(221, 255, 27); 

        card.className = "card";
        card.classList.add('card');
        card.setAttribute('id', carta.numero+'_'+carta.tipo);
        card.setAttribute('src', carta.rutaImagen);
        card.setAttribute('alt', carta.nombreLargo);
        card.setAttribute('height', '100%');
        card.style.cssText = 'border-radius: 4%; box-shadow: 0px 0px 20px 5px rgb(0, 0, 0); top: 0px;';
        card.style.padding = '4%';
        card.style.overflow = 'visible';
        card.style.position = 'relative'; // static|absolute|fixed|relative|sticky|initial|inherit
        //card.style.top = '0px;';
        
        //console.info(carta.nombreLargo);
        container.appendChild(card);
        board.appendChild(container);
        this.cardIteration++;
    }

    public retornarAyuda() {
        /*if (this.numeroIngresado < this.numeroSecreto) {
            return "Falta";
        }*/
        return "Te pasaste";
    }
}
