import { Component, OnInit } from '@angular/core';

@Component({
  selector: 'app-cabecera',
  templateUrl: './cabecera.component.html',
  styleUrls: ['./cabecera.component.css']
})
export class CabeceraComponent implements OnInit {
  loged: boolean;

  constructor() { }

  ngOnInit() {
    this.loged = this.getToken() !== false;
  }

  logout() {
    sessionStorage.removeItem('token');
    localStorage.removeItem('token');
    //location
    
  }

  getToken(){ // para usar:  headers: {"token": getToken()} dentro de la llamada (parametros)
    if(localStorage.getItem('token') !== null){
      return localStorage.getItem('token');
    }
    if(sessionStorage.getItem('token') !== null){
      return sessionStorage.getItem('token');
    }
    return false;
  }
  

}
