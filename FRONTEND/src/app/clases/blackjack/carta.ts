
import { Juego } from '../juego'
import { Mazo } from './mazo'

enum Tipo {Treboles = 1, Corazones = 2, Diamantes = 3, Picas = 4}

export class Carta {
    rutaImagen: string;     // ruta de la imagen de la carta
    tipo: Tipo;             // palo de la carta: 1 = trebol, 2 = corazon, 3 = diamante, 4 = pica
    numero: number;         // numero; as = 1, ... 11 = jack, 12 = queen, 13 = king
    enElMazo: boolean;      // si la carta todavia esta en el mazo, es verdadero
    nombreLargo: string;    // nombre completo de la carta
    indexJugador: number;   // indice del jugador que tiene la carta
    i: number;

    constructor(numero: number, tipo: Tipo, rutaImg: string) {
        this.numero = numero;
        this.tipo = tipo;
        this.rutaImagen = rutaImg;
        this.enElMazo = false;
        this.i = -50;
        let tmp: string;
        switch (numero) {
            case 1:
                tmp = 'As';
                break;
            
            case 11:
                tmp = 'Sota';
                break;
        
            case 12:
                tmp = 'Reina';
                break;
        
            case 13:
                tmp = 'Rey';
                break;
        
            default:
                tmp = numero.toString();
                break;
        }
        tmp += ' de ' + Tipo[tipo];
        this.nombreLargo = tmp;
    }
    
    public Flip() {
        let obj: HTMLElement = document.getElementById(this.numero+'_'+this.tipo);
        var i = -50;
        let iv = this.draw;
        
        //setInterval(iv, 100, this, obj, i[0], iv);
        this.rutaImagen = './assets/imagenes/cards/lg/'+this.numero+'_'+this.tipo+'.png';
        obj.parentElement.style.cssText += 'animation-duration: 250ms; animation: flip-top 250ms cubic-bezier(0.455, 0.030, 0.515, 0.955) reverse both;';
        obj.parentElement.classList.add('flip-right');
        obj.setAttribute('src', this.rutaImagen);
    }

    private draw(carta: Carta, obj: HTMLElement, i, iv: any) {
        console.log('i = '+this.i);
        i++;
        if (i < 0) {
            obj.style.width = (-1*i).toString()+'px';
            console.log('i < 0');
        } else if(i==0) {
            carta.rutaImagen = './assets/imagenes/cards/lg/'+carta.numero+'_'+carta.tipo+'.png';
            obj.setAttribute('src', carta.rutaImagen);
            console.log('i == 0');
        } else if (i == 100) {
            clearInterval(iv);
            console.log('clearInterval');
        } else if (i > 0) {
            obj.style.width = i.toString()+'px';
            console.log('i > 0');
        }
    }
}

