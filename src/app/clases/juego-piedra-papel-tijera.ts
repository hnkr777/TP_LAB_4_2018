
import { Juego } from '../clases/juego'
import { JuegoServiceService } from "../servicios/juego-service.service";
import { ArchivosJuegosServiceService } from "../servicios/archivos-juegos-service.service";

export class JuegoPiedraPapelTijera extends Juego {
    public readonly piedra : number = 1;
    public readonly papel : number = 2;
    public readonly tijera : number = 3;

    gano: number;
    empato: number;
    perdio: number;

    intentos: number; // contador

    private tag = ["Piedra", "Papel", "Tijera"];
    private jugada = ["Perdió", "Empató", "Ganó"];

    jugador : number = 0;
    puntosMaquina: number;
    puntosJugador: number;
    maquina : number = 0;
    
    constructor(public servicioJuego: JuegoServiceService) {
        super('PPT', "Piedra, papel o tijera");
        this.empato = 0;
        this.gano = 0;
        this.perdio = 0;
        this.nuevaPartida();
    }

    public nuevaPartida() {
        this.puntosMaquina = 0;
        this.puntosJugador = 0;
        this.intentos = 0;
    }

    public guardarJugada(): boolean {
        this.datos = JSON.stringify({
            ganadas: this.gano,
            empatadas: this.empato,
            perdidas: this.perdio
        });
        
        this.servicioJuego.guardarPartida('guardarjugada', this);
        console.info('Jugada guardada.');
        return true;
    }

    public jugar(eleccion: number): string {
        let ganador: number;
        this.intentos++;
        this.maquina =  Math.floor((Math.random() * 3) + 1);
        document.getElementById('rotacion').classList.remove('rad');
        document.getElementById('rotacion').classList.remove('Piedra');
        document.getElementById('rotacion').classList.remove('Papel');
        document.getElementById('rotacion').classList.remove('Tijera');
        document.getElementById('rotacion').classList.add(this.tag[this.maquina-1]);
        
        this.jugador = eleccion;
        ganador = this.verificar();
        if(this.intentos==3) {
            
            this.guardarJugada();
            this.nuevaPartida();
        }
        console.info('Maquina: '+this.tag[this.maquina-1]);
        console.info('Jugador: '+this.tag[this.jugador-1]);
        console.log('Gano jugador: '+ this.jugada[ganador+1]);
        return this.jugada[ganador+1];
    }

    public verificar(): number {
        let j: number = this.jugador;
        let m: number = this.maquina;
        if(m == j) {
            this.empato++;
            return 0;
        } else if( (j == this.piedra && m == this.tijera) || (j == this.papel && m == this.piedra) || (j == this.tijera && m == this.papel) ) {
            this.gano++;
            return 1;
        } else if( (m == this.piedra && j == this.tijera) || (m == this.papel && j == this.piedra) || (m == this.tijera && j == this.papel) ) {
            this.perdio++;
            return -1;
        }
    }

}
