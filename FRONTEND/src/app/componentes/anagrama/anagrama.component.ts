import { Component, OnInit,Input,Output,EventEmitter } from '@angular/core';
import { JuegoAnagrama } from '../../clases/juego-anagrama';
import { JuegoServiceService } from '../../servicios/juego-service.service';

@Component({
  selector: 'app-anagrama',
  templateUrl: './anagrama.component.html',
  styleUrls: ['./anagrama.component.css']
})
export class AnagramaComponent implements OnInit {

  miJuego: JuegoAnagrama;
  palabra: string;

  @Output()
  enviarJuego:EventEmitter<any>= new EventEmitter<any>();

  constructor(private miServicio?: JuegoServiceService) 
  { 
    this.miJuego = new JuegoAnagrama(miServicio);
  }

  ngOnInit() {
    this.miJuego.Comenzar();
  }

  rendirse() {
    this.miJuego.Rendirse();
    console.log('Se rindió.');
    this.enviarJuego.emit(this.miJuego);
    this.palabra = "";
  }

  comprobar() {
    if( this.miJuego.chequear(this.palabra) ) {
      this.enviarJuego.emit(this.miJuego);
      console.log('Ganó: '+this.miJuego.ganador);
      this.palabra = "";
      this.miJuego.Comenzar();
    }
  }

  /* this.unJuego.nombre= this.miServicio.retornarUsuario();
      this.enviarJuego.emit(this.unJuego);*/
}
