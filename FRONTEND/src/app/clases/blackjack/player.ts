
import { Jugador } from "../jugador";
import { Carta } from "./carta";

export class Player extends Jugador {
    nombre: string;
    cartas: Array<Carta>;
    suma: number;
    puntaje: number;
    ganadas: number;
    perdidas: number;
    empatadas: number;

    constructor(nombre?: string) {
        super();
        this.nombre = nombre !== undefined ? nombre: null;
        this.cartas = new Array<Carta>();
        this.suma = 0;
        this.puntaje = 0;
        this.ganadas = 0;
        this.perdidas = 0;
        this.empatadas = 0;
    }

    public recibirCarta(carta: Carta) {
        this.cartas.push(carta);
        this.suma += this.getCardValue(carta);
    }

    public nuevaMano() {
        this.suma = 0;
        this.cartas = new Array<Carta>();
    }

    public getCardValue(carta: Carta): number { // podria ir en la clase Carta, pero dado que el valor de los ases depende de otras cartas del jugador...
        let tmp: number;
        tmp = carta.numero;
        if (tmp > 10) {
            tmp = 10;
        } else if (tmp == 1 && this.suma < 11 && this.cartas.find(el => el.numero == 1)!==undefined) {
            tmp = 11;
        }
        return tmp;
    }
}