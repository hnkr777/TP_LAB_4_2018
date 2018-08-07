
import { Injectable } from '@angular/core';
import { Juego } from '../clases/Juego';
import { JuegoAdivina } from '../clases/juego-adivina';
import { MiHttpService } from './mi-http/mi-http.service'; 
import { ArchivosJuegosServiceService } from "../servicios/archivos-juegos-service.service";
import { environment } from './../../environments/environment';

@Injectable()
export class JuegoServiceService {
  api: string = environment.backendRoute+"partidas/traertodas/";
  peticion: any;
  filtrado: any;

  constructor( public miHttp: MiHttpService, public miServiceJuegos: ArchivosJuegosServiceService ) {
    //this.filtrado = new Array<any>();
    this.peticion = this.miHttp.httpGetO(this.api);
  }

  guardarPartida(ruta: string, juego: Juego) {
    return this.miServiceJuegos.guardarJuego(ruta, juego)
    .then(data => {
      return data;
    }).catch(error => {
      console.log("Error guardando partida");
      return error;
    });
  }

  traerPartidas(ruta: string, filtro: string) {
    return this.miServiceJuegos.traerJuegos(ruta).then(data=>{
      console.info('pasamos por traerPartidas()');
      let arr: Array<any> = new Array<any>();
      this.filtrado = data;

      if(filtro != '') {
        this.filtrado = this.filtrado.filter(
          data => data.juego == filtro
        );
      }

      this.filtrado.forEach((el, i: number)=>{
        let obj: any;
        obj = JSON.parse(el.datos)
        obj.nombre = el.nombre;
        obj.juego = el.juego;
        //console.info(obj);
        arr.push(obj);
        //console.info(arr);
      });
      this.filtrado = arr;

      return this.filtrado;
    }).catch(error=>{
      console.log("traerPartidas: Error");
      return this.filtrado;
    });
  }

  public listar(): any { //public listar(): Array<Juego> {
    this.traerPartidas('traertodas/', '');
    //let miArray: Array<Juego> = new Array<Juego>();

    // this.miHttp.httpGetP("localhost/partidas/traertodas/")
    // .then( data => {
    //   console.log( 'En listar()' );
    //   //var obj: any;
    //   //obj = JSON.parse(data);
    //   return data;
    //   //miArray = 
    //   //alert(obj);
    // })
    // .catch( err => {
    //   alert( JSON.stringify(err));
    // });
   
  
    // this.peticion
    // .subscribe( data => {
    //   console.log("En listar: "+JSON.stringify(data));
    //   //var obj: any;
    //   //miArray = JSON.parse(JSON.stringify(data));
    //   //console.log("En listar: "+JSON.stringify(miArray));
    //   return data;
    // }, err => {
    //   console.info("error: ", err );
    // })

    // miArray.forEach((el, i)=>{
    //   console.log('datos=== ' +el.datos);
    // });

    //return miArray;
  }

}
