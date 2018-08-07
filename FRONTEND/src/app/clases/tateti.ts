
import { Juego } from './juego'
import { Jugador } from './jugador'
import { TatetiComponent } from "../componentes/tateti/tateti.component";
import { setInterval } from 'timers';
import { JuegoServiceService } from "../servicios/juego-service.service";
import { ArchivosJuegosServiceService } from "../servicios/archivos-juegos-service.service";

export class Tateti extends Juego {
    gana: boolean;
    empata: boolean;
    pierde: boolean;

    private tmp: string = "";

    mat: Array<number> = [0, 0, 0, 0, 0, 0, 0, 0, 0];

    contador: number;
    public partidaTerminada: number;

    private jugada = ["Perdió", "Empató", "Ganó"];

    jugador : number = 0;
    puntosMaquina: number;
    puntosJugador: number;
    maquina : number = 0;
    
    constructor(public servicioJuego: JuegoServiceService) {
        super('Tateti', "Ta Te Ti");
        this.empata = false;
        this.gana = false;
        this.pierde = false;
        this.nuevaPartida();
    }

    public nuevaPartida() {
        this.contador = 0;
        this.mat = [0, 0, 0, 0, 0, 0, 0, 0, 0];
        this.partidaTerminada = 0;
        this.tmp = '';
        
        if(document.getElementsByClassName('boton').item(1)!==null)
        for (let i = 0; i < 9; i++) {
            document.getElementsByClassName('boton').item(i).classList.remove('selected');
            document.getElementsByClassName('boton').item(i).classList.remove('cruz');
            document.getElementsByClassName('boton').item(i).classList.remove('circulo');
        }
        
    }

    public guardarJugada(): boolean {
        this.datos = JSON.stringify({
            gano: this.gana,
            empato: this.empata,
            perdio: this.pierde
        });
        
        this.servicioJuego.guardarPartida('guardarjugada', this);
        console.info('Jugada guardada.');
        return true;
    }

    public jugar(eleccion: number): string {
        if(this.partidaTerminada == 1) {
            this.partidaTerminada = -1;
            return 'Partida terminada. Click para empezar otra.';
        } else if(this.partidaTerminada == -1) {
            this.nuevaPartida();
            return 'Nueva partida, juega con cruces';
        }

        if(this.mat[eleccion-1] !=0 ) {
            return 'Celda ocupada, elija otra.';
        }
        
        this.contador++;
        
        console.info('celda: '+ eleccion);
        document.getElementsByClassName('boton').item(eleccion-1).classList.add('selected');
        document.getElementsByClassName('boton').item(eleccion-1).classList.add('cruz');
        this.mat[eleccion-1] = 1;
        this.jugador = eleccion;

        if(this.comprobar() != -2)
            return this.tmp;

        if(this.contador == 5)
            return 'Empate';
        
        this.jugarMaquina();
        this.comprobar();

        return this.tmp;
    }

    private comprobar(): number {
        let ganador: number = this.verificar();
        
        if(this.contador==5 || ganador!=0) {
            this.gana = ganador == 1;
            this.pierde = ganador == -1;
            this.empata = ganador == 0;
            this.guardarJugada();
            this.partidaTerminada = 1;
            this.tmp = this.jugada[ganador+1];
            
            console.log('Partida terminada, el jugador '+ this.jugada[ganador+1]);
            return ganador;
        }
        return -2;
    }

    public jugarMaquina() {
        let cant: number = 0;
        //let pares: number = 0, impares: number = 0;
        //let suma: number = 0;
        let m: number;
        let c = this.mat;

        for (let i = 1; i < 10; i++) { 
            if(c[i-1] == -1) {  // analizamos nuestras posiciones...
                //if(i%2==0) pares++;
                //else impares++;
                //suma += i;
                cant++;
            }
        }
        
        m = this.chooseMachine( cant );
        
        this.maquina = m;
        document.getElementsByClassName('boton').item(m-1).classList.add('selected');
        document.getElementsByClassName('boton').item(m-1).classList.add('circulo');
        this.mat[m-1] = -1;
    }

    private chooseMachine(cant: number): number {
        let c = this.mat;
        let m: number;
        let tmp: string = '# Maquina: ataque';
        if(cant > 0) {
            if( 0==c[1-1] && ( c[2-1]+c[3-1]==-2 || c[5-1]+c[9-1]==-2 || c[4-1]+c[7-1]==-2 ) ) { // 1
                m = 1;
            } else if( 0==c[2-1] && ( c[1-1]+c[3-1]==-2 || c[5-1]+c[8-1]==-2 ) ) { // 2
                m = 2;
            } else if( 0==c[3-1] && ( c[1-1]+c[2-1]==-2 || c[5-1]+c[7-1]==-2 || c[6-1]+c[9-1]==-2 ) ) { // 3
                m = 3;
            } else if( 0==c[4-1] && ( c[1-1]+c[7-1]==-2 || c[5-1]+c[6-1]==-2 ) ) { // 4
                m = 4;
            } else if (0==c[5-1] && ( c[1-1]+c[9-1]==-2 || c[2-1]+c[8-1]==-2 || c[3-1]+c[7-1]==-2 || c[4-1]+c[6-1]==-2 )) { // 5
                m = 5;
            } else if( 0==c[6-1] && ( c[9-1]+c[3-1]==-2 || c[5-1]+c[4-1]==-2  ) ) { // 6
                m = 6;
            } else if( 0==c[7-1] && ( c[1-1]+c[4-1]==-2 || c[5-1]+c[3-1]==-2 || c[8-1]+c[9-1]==-2 ) ) { // 7
                m = 7;
            } else if( 0==c[8-1] && ( c[2-1]+c[5-1]==-2 || c[7-1]+c[9-1]==-2 ) ) { // 8
                m = 8;
            } else if( 0==c[9-1] && ( c[1-1]+c[5-1]==-2 || c[3-1]+c[6-1]==-2 || c[7-1]+c[8-1]==-2 ) ) { // 9
                m = 9;
            } else {
                tmp = '# Maquina: defensa.';
                if(this.contador > 0) {
                    if( 0==c[1-1] && ( c[2-1]+c[3-1]==2 || c[5-1]+c[9-1]==2 || c[4-1]+c[7-1]==2 ) ) { // 1
                        m = 1;
                    } else if( 0==c[2-1] && ( c[1-1]+c[3-1]==2 || c[5-1]+c[8-1]==2 ) ) { // 2
                        m = 2;
                    } else if( 0==c[3-1] && ( c[1-1]+c[2-1]==2 || c[5-1]+c[7-1]==2 || c[6-1]+c[9-1]==2 ) ) { // 3
                        m = 3;
                    } else if( 0==c[4-1] && ( c[1-1]+c[7-1]==2 || c[5-1]+c[6-1]==2 ) ) { // 4
                        m = 4;
                    } else if (0==c[5-1] && ( c[1-1]+c[9-1]==2 || c[2-1]+c[8-1]==2 || c[3-1]+c[7-1]==2 || c[4-1]+c[6-1]==2 )) { // 5
                        m = 5;
                    } else if( 0==c[6-1] && ( c[9-1]+c[3-1]==2 || c[5-1]+c[4-1]==2  ) ) { // 6
                        m = 6;
                    } else if( 0==c[7-1] && ( c[1-1]+c[4-1]==2 || c[5-1]+c[3-1]==2 || c[8-1]+c[9-1]==2 ) ) { // 7
                        m = 7;
                    } else if( 0==c[8-1] && ( c[2-1]+c[5-1]==2 || c[7-1]+c[9-1]==2 ) ) { // 8
                        m = 8;
                    } else if( 0==c[9-1] && ( c[1-1]+c[5-1]==2 || c[3-1]+c[6-1]==2 || c[7-1]+c[8-1]==2 ) ) { // 9
                        m = 9;
                    } else {
                        tmp = '# Maquina: aleatorio.';
                        m = this.selRandom();
                    }
                } else {
                    tmp = '# Maquina: aleatorio.';
                    m = this.selRandom();
                }
            }
        } else if(c[5-1]==0) {
            m = 5;
        } else {
            tmp = '# Maquina: aleatorio.';
            m = this.selRandom();
        }

        console.info(tmp);

        return m;
    }

    private funcProto() {
        
    }

    private selRandom(): number {
        let m: number;
        do {
            m =  Math.floor((Math.random() * 9) + 1);
        } while(this.mat[m-1] != 0 && this.contador < 5);
        //console.log('selRandom['+m+']');
        return m;
    }

    public verificar(): number {
        let m = this.mat;
        let ganador: number = 0;

        if(m[0] == m[3] && m[3] == m[6] && m[6] != 0) {
            ganador = m[0];
        } else if(m[1] == m[4] && m[4] == m[7] && m[7] != 0) {
            ganador = m[1];
        } else if(m[2] == m[5] && m[5] == m[8] && m[8] != 0) {
            ganador = m[2];
        }

        else if(m[0] == m[1] && m[1] == m[2] && m[2] != 0) {
            ganador = m[0];
        } else if(m[3] == m[4] && m[4] == m[5] && m[5] != 0) {
            ganador = m[3];
        } else if(m[6] == m[7] && m[7] == m[8] && m[8] != 0) {
            ganador = m[6];
        }

        else if(m[0] == m[4] && m[4] == m[8] && m[8] != 0) {
            ganador = m[0];
        } else if(m[2] == m[4] && m[4] == m[6] && m[6] != 0) {
            ganador = m[2];
        }

        return ganador;
    }

}
