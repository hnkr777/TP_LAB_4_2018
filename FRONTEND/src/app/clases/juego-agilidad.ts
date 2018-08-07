
import { Juego } from '../clases/juego'
import { JuegoServiceService } from '../servicios/juego-service.service';

export class JuegoAgilidad extends Juego {

	ganador: boolean;
	arrayOperandos: Array<string> = ["+","-","*","/"];
	operando: string;
	solucion: number;
	intentos: number;

 	constructor(public miService: JuegoServiceService) {
		 super('Velocidad', 'Agilidad y velocidad aritmética');
		 this.ganador = false;
		 this.intentos = 0;
		 this.solucion = undefined;
    }
    
    public guardarJugada(): boolean {
		this.datos = JSON.stringify({
			gano: this.ganador,
			solucion: this.solucion,
            intentos: this.intentos
        });
        
        this.miService.guardarPartida('guardarjugada', this);
        console.info('Jugada guardada.');
        return true;
    }

	public generarNuevo(): string {
		let res: string = "";
		this.intentos = 0;
		this.ganador = false;
		let op1: number, op2: number;
		this.operando = this.arrayOperandos[ Math.floor( ( Math.random() * this.arrayOperandos.length ) ) ];
		
		switch(this.operando) {
			case "+":
				op1 = this.getNumber(100);
				op2 = this.getNumber(100);
				this.solucion =  op1 + op2;
				res = op1.toString() + ' + ' + op2.toString();
				break;
			case "-":
				op1 = this.getNumber(100);
				op2 = this.getNumber(100);
				this.solucion =  op1 - op2;
				res = op1.toString() + ' - ' + op2.toString();
				break;
			case "*":
				op1 = this.getNumber(12);
				op2 = this.getNumber(12);
				this.solucion =  op1 * op2;
				res = op1.toString() + ' * ' + op2.toString();
				break;
			case "/":
				do {
					op1 = this.getNumber(200);
					op2 = this.getNumber(100);
				} while(op2 === 0 || op1 % op2 != 0);
				this.solucion =  op1 / op2;
				res = op1.toString() + ' / ' + op2.toString();
				break;
		}

		return res;
	}

	public chequear(numero: number): boolean {
		if(this.solucion===undefined) return false;
		
		if(this.solucion == numero) {
			this.ganador = true;
			this.guardarJugada();
			return true;
		}
		this.intentos++;
		return false;
	}

	private getNumber(max: number) {
		return Math.floor((Math.random() * max) + 1);
	}

	verificar(): number {
		return 1;
	}

}
