import { Component, OnInit } from '@angular/core';
import { JuegoServiceService } from '../../servicios/juego-service.service';

@Component({
  selector: 'app-listado',
  templateUrl: './listado.component.html',
  styleUrls: ['./listado.component.css']
})
export class ListadoComponent implements OnInit {
  public listadoParaCompartir: Array<any>;
  public show: any;

  miServicioJuego:JuegoServiceService;

  constructor(servicioJuego:JuegoServiceService) {
    this.miServicioJuego = servicioJuego;
    this.setFalse();
  }
  
  ngOnInit() {
    this.traerTodos();
  }

  private setFalse() {
    this.show = {adivina: false, anagrama: false, blackjack: false, ppt: false, tateti: false, velocidad: false};
  }

  traerTodos() {
    console.log("traerTodos()");
    this.miServicioJuego.traerPartidas('traertodas','').then(data=>{
      //console.info('=== '+JSON.stringify(data));
      this.show.adivina = true;
      this.show.anagrama = true;
      this.show.blackjack = true;
      this.show.ppt = true;
      this.show.tateti = true;
      this.show.velocidad = true;

      this.listadoParaCompartir = data;
    })
  }

  traerFiltrado(filtro: string){
    console.log("traerFiltrado("+filtro+")");
    this.setFalse();
    switch (filtro) {
      case 'Adivina':
        this.show.adivina = true;
        break;
      
      case 'Anagrama':
        this.show.anagrama = true;
        break;
      
      case 'BlackJack':
        this.show.blackjack = true;
        break;
      
      case 'PPT':
        this.show.ppt = true;
        break;

      case 'Tateti':
        this.show.tateti = true;
        break;
      
      case 'Velocidad':
        this.show.velocidad = true;
        break;
    
      default:
        break;
    }
    this.miServicioJuego.traerPartidas('traertodas', filtro).then(data=>{
      this.listadoParaCompartir = data;
    })
  }
}
