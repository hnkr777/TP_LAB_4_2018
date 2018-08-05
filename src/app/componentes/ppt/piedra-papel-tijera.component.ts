import { Component, OnInit } from '@angular/core';
import { Juego } from '../../clases/juego';
import { JuegoPiedraPapelTijera } from '../../clases/juego-piedra-papel-tijera';
import { JuegoServiceService } from '../../servicios/juego-service.service';


@Component({
  selector: 'app-piedra-papel-tijera',
  templateUrl: './piedra-papel-tijera.component.html',
  styleUrls: ['./piedra-papel-tijera.component.css']
})
export class PiedraPapelTijeraComponent implements OnInit {
  nuevoJuego : JuegoPiedraPapelTijera;
  
  mensaje: string = 'Gana el mejor de tres intentos';

  constructor(public juegoSrv : JuegoServiceService ) {
    this.nuevoJuego = new JuegoPiedraPapelTijera(this.juegoSrv);
  }

  ngOnInit() {
    document.getElementById('rotacion').classList.add('rad');
  }

  elijoPiedra() {
    this.mensaje = this.nuevoJuego.jugar(this.nuevoJuego.piedra);
  }

  elijoPapel() {
    this.mensaje = this.nuevoJuego.jugar(this.nuevoJuego.papel);
  }

  elijoTijera() {
    this.mensaje = this.nuevoJuego.jugar(this.nuevoJuego.tijera);
  }

  verificar() {
    
  }

}
