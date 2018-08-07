
import { Component, OnInit, Input, Output, EventEmitter} from '@angular/core';
import { Tateti } from '../../clases/tateti'
import { JuegoServiceService } from "../../servicios/juego-service.service";


@Component({
  selector: 'app-tateti',
  templateUrl: './tateti.component.html',
  styleUrls: ['./tateti.component.css']
})
export class TatetiComponent implements OnInit {
  nuevoJuego : Tateti;
  
  mensaje: string = 'Su turno, juega con cruces';

  constructor(public juegoSrv : JuegoServiceService ) {
    this.nuevoJuego = new Tateti(this.juegoSrv);
  }

  ngOnInit() {
    //document.getElementById('rotacion').classList.add('rad');
  }

  elijo(celda: number) {
    this.mensaje = this.nuevoJuego.jugar(celda);
  }


  verificar() {
    
  }

}
