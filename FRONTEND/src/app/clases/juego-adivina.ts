
import { Juego } from '../clases/juego'
import { JuegoServiceService } from "../servicios/juego-service.service";

export class JuegoAdivina extends Juego {
    numeroSecreto: number = 0;
    numeroIngresado = 0;
    ganador: boolean;
    intentos: number = 0;
    tiempo: number;

    constructor(public miService: JuegoServiceService) {
      super("Adivina", 'Adivina el número');
      this.intentos = 0;
      this.tiempo = 0;
    }

    public guardarJugada() {
      this.datos = JSON.stringify({
        numero: this.numeroSecreto,
        tiempo: this.tiempo,
        intentos: this.intentos
      });
    
      this.miService.guardarPartida('guardarjugada', this);
      console.info('Jugada guardada.');

      return true;
    }

    public verificar() {
      let g: number = 0;
      if (this.numeroIngresado == this.numeroSecreto) {
        this.ganador = true;
        this.tiempo = Math.floor(new Date().getTime() / 1000 - this.tiempo);
        this.guardarJugada();
        g = 1;
      } else {
        g = 0;
        this.intentos++;
      }

      return g;
    }

     public generarnumero() {
        this.numeroSecreto = Math.floor((Math.random() * 100) + 1);
        console.info('numero Secreto:' + this.numeroSecreto);
        this.ganador = false;
        this.tiempo = Math.floor(new Date().getTime() / 1000);
      }
      
      public retornarAyuda() {
        if (this.numeroIngresado < this.numeroSecreto) {
          return "Falta";
        }
        return "Te pasate";
      }
}
