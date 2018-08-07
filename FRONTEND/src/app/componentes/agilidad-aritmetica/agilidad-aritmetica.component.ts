import { Component, OnInit ,Input,Output,EventEmitter} from '@angular/core';
import { JuegoAgilidad } from '../../clases/juego-agilidad'

import {Subscription} from "rxjs";
import {TimerObservable} from "rxjs/observable/TimerObservable";
import { JuegoServiceService } from '../../servicios/juego-service.service';

@Component({
  selector: 'app-agilidad-aritmetica',
  templateUrl: './agilidad-aritmetica.component.html',
  styleUrls: ['./agilidad-aritmetica.component.css']
})
export class AgilidadAritmeticaComponent implements OnInit {
   @Output() 
  enviarJuego :EventEmitter<any>= new EventEmitter<any>();
  nuevoJuego : JuegoAgilidad;
  resolver: string = "";
  numeroIngresado: number;
  intentos: number;
  ocultarVerificar: boolean;
  Tiempo: number;
  repetidor: any;
  mensaje: string;

  private subscription: Subscription;

  ngOnInit() {
    this.mensaje = "";
  }

  constructor(public miServicio: JuegoServiceService) {
    this.ocultarVerificar = true;
    this.Tiempo = 20;
    this.nuevoJuego = new JuegoAgilidad(this.miServicio);
  }

  NuevoJuego() {
    this.ocultarVerificar = false;
    this.resolver = this.nuevoJuego.generarNuevo();
    this.repetidor = setInterval(()=>{
      this.Tiempo--;

      if(this.Tiempo == 0 ) {
        clearInterval(this.repetidor);
        this.verificar();
        this.mensaje = "Tiempo terminado.";
        this.nuevoJuego.guardarJugada();
        this.ocultarVerificar = true;
        this.Tiempo = 20;
      }
    }, 900);
  }

  verificar() {
    
    if(this.nuevoJuego.chequear(this.numeroIngresado)) {
      this.mensaje = "Respuesta correcta.";
      this.ocultarVerificar = true;
      this.Tiempo = 20;
      clearInterval(this.repetidor);
    } else {
      this.mensaje = "Siga intentando.";
    }
    
  }

}

