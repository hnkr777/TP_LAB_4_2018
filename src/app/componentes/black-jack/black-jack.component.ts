
import { Component, OnInit, Input, Output, EventEmitter} from '@angular/core';
import { BlackJack } from '../../clases/blackjack/juego-black-jack'
import { JuegoServiceService } from "../../servicios/juego-service.service";

@Component({
  selector: 'black-jack',
  templateUrl: './black-jack.component.html',
  styleUrls: ['./black-jack.component.css']
})

export class BlackJackComponent implements OnInit {
  @Output() enviarJuego: EventEmitter<any>= new EventEmitter<any>();
  nuevoJuego: BlackJack;
  Mensajes: string;
  contador: number;
  ocultarVerificar: boolean;
 
  constructor(public miJuego: JuegoServiceService) {
    this.nuevoJuego = new BlackJack(this.miJuego);
      this.ocultarVerificar=false;
  }
  
  guardar() {
    // guardar en la db?
    //let obj = this.miJuego.guardarPartida('guardarjugada', this.nuevoJuego);
    //console.info('Partida guardada.');
    this.enviarJuego.emit(this.nuevoJuego); //this.enviarJuego.emit(this);
  }

  pedir() {
    this.nuevoJuego.Pedir(1);  // TEMPORAL: indice jugador
    this.Mensajes = 'Pedir nueva carta';
  }

  reset() {
    this.nuevoJuego.Reset();
    this.contador=0;
  }

  plantarse() {
    this.nuevoJuego.Plantarse();
  }

  repartir() {
    this.nuevoJuego.Repartir();
  }

  verificar()
  {
    if(this.nuevoJuego.verificar()) {
      this.MostrarMensaje("", true);
    }
  }

  MostrarMensaje(mensaje: string = "este es el mensaje", ganador: boolean = false) {
    this.Mensajes = mensaje;
    var x = document.getElementById("snackbar");
    if(ganador) {
      x.className = "show Ganador";
    } else {
      x.className = "show Perdedor";
    }
    var modelo = this;
    setTimeout(function() { 
      x.className = x.className.replace("show", "");
      modelo.ocultarVerificar=false;
    }, 3000);
    console.info("objeto",x);
  }

  ngOnInit() {
    document.getElementById('info').setAttribute('src', 'assets/imagenes/info-2.png');
    document.body.setAttribute('src', './assets/imagenes/back.jpg');
    document.body.setAttribute('background-color', 'gray');
  }

}
