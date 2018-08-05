import { Component, OnInit } from '@angular/core';
import { Router, ActivatedRoute, ParamMap } from '@angular/router';
import { MiHttpService } from '../../servicios/mi-http/mi-http.service';
import {Subscription} from 'rxjs';
import {TimerObservable} from 'rxjs/observable/TimerObservable';
import { environment } from '../../../environments/environment';
import { isDefined } from '@angular/compiler/src/util';

@Component({
  selector: 'app-login',
  templateUrl: './login.component.html',
  styleUrls: ['./login.component.css']
})
export class LoginComponent implements OnInit {

  private subscription: Subscription;
  email: string;
  clave: string;
  recordarme: boolean;

  token: any;

  progreso: number;
  progresoMensaje = 'esperando...';
  logeando = true;
  ProgresoDeAncho: string;
  xml: XMLHttpRequest;

  clase = 'progress-bar progress-bar-info progress-bar-striped ';

  constructor(
    private xhr: MiHttpService,
    private route: ActivatedRoute,
    private router: Router) {
      this.recordarme = false;
  }

  ngOnInit() {
    let token = this.getToken();
    if ( token !== false ) {
      this.router.navigate(['/Principal']);
    }
  }

  login(type: number) {
    switch (type) {
      case 1:
        this.email = 'Encargado';
        this.clave = '123';
        break;

      case 2:
        this.email = 'Conductor';
        this.clave = '123';
        break;

      case 3:
        this.email = 'cliente@humberto.com';
        this.clave = '123';
        break;
    
      default:
        break;
    }
  }

  completado(res: Response) {
    let ob = JSON.parse(JSON.stringify(res.json()));
    console.log('¡Acceso concedido! Bienvenido ' + ob.perfil);
    console.warn(ob.SessionToken);
    sessionStorage.setItem('token', ob.SessionToken);
    return res.json() || {};
  }

  error( error: Response | any ) {
    console.error('¡Acceso denegado!');
    // console.log(JSON.stringify(error.json()));
    let msg: string = JSON.stringify(error);
    let obj = JSON.parse(msg);
    if ((obj.statusText !== undefined) && obj.ok === false) {
      console.log('Error ' + obj.status + ': service url(' + obj.url + ') ' + obj.statusText);
    } else {
      console.log(msg);
    }
    return error;
  }

  ingresar() {
    const obj = {email: this.email, password: this.clave};
    this.xhr.httpPostS(environment.backendRoute + 'login', obj, this.completado, this.error, () => {
      if (this.recordarme) {
        localStorage.setItem('token', sessionStorage.getItem('token'));
        sessionStorage.removeItem('token');
      }
      this.router.navigate(['/Principal']);
    });
  }

  logout() {
    sessionStorage.removeItem('token');
    localStorage.removeItem('token');
    location.reload();
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

  toggleRecordarme() {
    this.recordarme = !this.recordarme;
  }

}
