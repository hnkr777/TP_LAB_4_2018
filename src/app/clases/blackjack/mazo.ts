
import { Juego } from '../juego'
import { Carta } from './carta'

export class Mazo {
    contador: number;
    cartasRestantes: number;
    cartas: Array<Carta>;

    constructor() {
        this.reiniciar();
    }
    
    public nuevaCarta(oculta: boolean):Carta {
        if (this.contador==52 || this.cartasRestantes==0 || this.cartas.length >= 52) {
            console.info('Mazo de cartas vacío.');
            return null;
        }
        this.contador++;
        this.cartasRestantes--;
        let nueva: Carta;
        let numero: number;
        let tipo: number;

        do {
            numero = Math.floor((Math.random() * 13) + 1);
            tipo = Math.floor((Math.random() * 4) + 1);
        } while (this.cartas.find(el => el.numero == numero && el.tipo == tipo) !== undefined);
        
        nueva = new Carta(numero, tipo, oculta?'./assets/imagenes/cards/lg/0.png':'./assets/imagenes/cards/lg/'+numero+'_'+tipo+'.png');
        this.cartas.push(nueva);
        return nueva;
    }

    public reiniciar() {
        this.contador = 0;          // contador de las cartas que ya salieron del mazo
        this.cartasRestantes = 52;  // baraja de 52 cartas
        this.cartas = new Array<Carta>();
    }
}