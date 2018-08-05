import { Injectable } from '@angular/core';
import { MiHttpService } from './mi-http/mi-http.service'; 
import { Headers, RequestOptions } from '@angular/http';
import { Juego } from '../clases/juego';
import { environment } from '../../environments/environment';

@Injectable()
export class ArchivosJuegosServiceService {
  api = environment.backendRoute+"partidas/";
  
  peticion:any;
  
  constructor( public miHttp: MiHttpService ) {
    
  }

  public guardarJuego(ruta: string, juego: Juego) {
    let headers = new Headers();
    let token = this.getToken();
    headers.append('token', token);
    let options = new RequestOptions({ headers: headers });

    return this.miHttp.httpPostO(this.api+ruta, { juego: juego.nombre, datos: juego.datos}, options)
    .toPromise()
    .then( data => {
      console.log("Archivo Juegos");
     // console.log( data );
      return data;
    }, err => {
      console.log( 'Error: '+err );
    })
  }

  public traerJuegos(ruta) {
    let headers = new Headers();
    let token = this.getToken();
    headers.append('token', token);
    let options = new RequestOptions({ headers: headers });

    return this.miHttp.httpGetO(this.api+ruta, options)
    .toPromise()
    .then( data => {
      console.log("Archivo Juegos");
     // console.log( data );
      return data;
    }, err => {
      console.log( err );
    })
  }

  getToken(): string { // para usar:  headers: {"token": getToken()} dentro de la llamada (parametros)
    if(localStorage.getItem('token') !== null){
      return localStorage.getItem('token');
    }
    if(sessionStorage.getItem('token') !== null){
      return sessionStorage.getItem('token');
    }
    return '';
  }

}
