
import { Component, OnInit , Input, EventEmitter} from '@angular/core';
import { JuegoServiceService } from "../../servicios/juego-service.service";

@Component({
  selector: 'app-listado-de-resultados',
  templateUrl: './listado-de-resultados.component.html',
  styleUrls: ['./listado-de-resultados.component.css']
})
export class ListadoDeResultadosComponent implements OnInit {
  @Input()
  listado: Array<any>; // el listado que se muestra
  @Input() showTable: any = {adivina: true, anagrama: true, blackjack: true, ppt: true, tateti: true, velocidad: true};
  
  locJuegos: JuegoServiceService;

  constructor(misJuegos: JuegoServiceService) {
    this.locJuegos = misJuegos;
    this.listado = new Array<any>();
  }

  ngOnInit() {

  }


  TraerTodas() { // no se usa
    this.locJuegos.traerPartidas('traertodas','todos').then(data=>{
       this.listado = data;
    })
  }

  ver() {
    console.info(this.listado);
  }

}
