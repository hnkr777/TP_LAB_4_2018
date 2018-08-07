
export abstract class Juego {
  public nombre = 'Juego';
  //public jugador: string;
  //public gano = false;
  public titulo: string;
  public id: number;
  public datos: string;

  public readonly perdio : number = -1;
  public readonly empato : number = 0;
  public readonly gano : number = 1;

  constructor(juego: string, titulo: string) {
    this.titulo = titulo;
    this.nombre = juego;
  }

  //'/partidas/guardarjugada'
  public abstract guardarJugada(): boolean;

  public abstract verificar(): number; // perdio = -1 / empato = 0 / gano = 1
  
  public retornarAyuda() {
    return "NO hay Ayuda definida";
  }
}
