import { Injectable } from '@angular/core';
import { MiHttpService } from './mi-http/mi-http.service'; 
import { Headers, RequestOptions } from '@angular/http';
import { environment } from '../../environments/environment';

@Injectable()
export class ArchivosJugadoresService {
  // api="http://localhost:8080/jugadoresarchivo/apirestjugadores/";
  api =  environment.backendRoute+"jugadoresarchivo/apirestjugadores/";
  peticion: any;

  constructor( public miHttp: MiHttpService ) {
    
  }

  public traerJugadores(ruta: string) {
    let headers = new Headers();
    let token = this.getToken();
    headers.append('token', token);
    let options = new RequestOptions({ headers: headers });
    console.log("Archivo jugadores...["+this.api+ruta+']');

    if(this.getToken() != '') {
      
    }
    
    return this.miHttp.httpGetO(this.api+ruta, options)
    .toPromise()
    .then( data => {
      console.log("Archivo jugadores");
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
