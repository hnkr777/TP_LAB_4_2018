import { Juego } from "./juego";
import { JuegoServiceService } from "../servicios/juego-service.service";

export class JuegoAnagrama extends Juego{

    palabrasDesordenadas: Array<string> = new Array<string>();
    palabrasOrdenadas: Array<string> = new Array<string>();
    palabra: string;
    palabraDeUsuario: string = '';
    contador: number = 3;
    index: number = 0;
    jugador: string = '';
    resultado: string = '';
    ganador: boolean = false;

    constructor(public servicioJuego: JuegoServiceService) {
        super('Anagrama', 'Anagrama');
        this.__init();
        this.Comenzar();
    }

    private __init() {
        this.palabrasOrdenadas=[
            "BUENOS AIRES",
            "MADRID",
            "BRUSELAS",
            "TOKIO",
            "NUEVA YORK",
            "BERLIN",
            "LISBOA",
            "MADRID",
            "LONDRES",
            "HONG KONG",
            "ESTAMBUL",
            "MOSCU",
            "AMSTERDAM",
            "WASHINGTON",
            "OTTAWA",
            "SIDNEY",
            "NUEVA DELHI",
            "SAN PABLO",
            "USHUAIA"
        ];
    }

    Comenzar() {
        
        this.index = Math.floor((Math.random() * (this.palabrasOrdenadas.length-1)) + 0);
        let pal: string = this.palabrasOrdenadas[this.index];
        console.log('Palabra elegida: '+pal);
        
        pal = this.desordenar(pal);

        console.log('Palabra desordenada: '+pal);

        this.palabra = pal;
        this.contador = 3;
        this.ganador = false;
    }

    private desordenar(pal: string): string {
        let a = pal.split("");
        let n: number = a.length;

        for(var i = n - 1; i > 0; i--) {
            var j = Math.floor(Math.random() * (i + 1));
            var tmp = a[i];
            a[i] = a[j];
            a[j] = tmp;
        }
        return a.join("");
    }

    verificar(): number { 
        return 0;
    }

    guardarJugada(): boolean { 
        this.datos = JSON.stringify({
            gano: this.ganador,
            palabra: this.palabra,
            intentos: this.contador
        });
        
        this.servicioJuego.guardarPartida('guardarjugada', this);
        console.info('Jugada guardada.');

        return true;
    }

    chequear(palabra: string): boolean {
        if(palabra != '' && palabra != null) {

            palabra = palabra.toUpperCase();
            console.log(palabra);
            console.log(this.palabrasOrdenadas[this.index])

            if(palabra === this.palabrasOrdenadas[this.index]) {
                this.ganador = true;
                this.resultado = "Ganó";
                this.guardarJugada();
            } else {
                this.contador--;
                this.resultado = "Palabra errónea, intente otra vez";
            }

            if(this.contador == 0) {
                this.ganador = false;
                this.resultado = "Perdió, se quedó sin intentos";
                this.guardarJugada();
                this.Comenzar();
            }
            
        }

        return this.ganador;
    }   

    Rendirse() {
        this.ganador = false;
        this.resultado = "Se rindió";
        this.guardarJugada();
        this.contador = 0;
        this.Comenzar();
    }
}
