import { Component, OnInit, Output, EventEmitter } from '@angular/core';
import { Router, ActivatedRoute, ParamMap } from '@angular/router';
import { MiHttpService } from '../../servicios/mi-http/mi-http.service';
import {Subscription} from 'rxjs';
import {TimerObservable} from 'rxjs/observable/TimerObservable';
import { environment } from '../../../environments/environment';
import { isDefined } from '@angular/compiler/src/util';
import { MsgComponent, TipoMensaje } from '../msg/msg.component';

declare var $: any;


@Component({
  selector: 'app-login',
  templateUrl: './login.component.html',
  styleUrls: ['./login.component.css']
})
export class LoginComponent implements OnInit {
  public msg: string;
  public title: string;
  public tipo: TipoMensaje;

  private subscription: Subscription;
  email: string;
  clave: string;
  recordarme: boolean;
  mensaje: string;
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
    console.warn('Token: ['+ob.SessionToken+']');
    sessionStorage.setItem('token', ob.SessionToken);
    return res.json() || {};
  }

  error( error: Response | any ) {
    console.error('¡Acceso denegado!');
    let msg: string = JSON.stringify(error);
    //console.error(msg);
    let obj = JSON.parse(msg);
    if ((obj.statusText !== undefined) && obj.ok === false) {
      console.error('Error ' + obj.status + ': service url(' + obj.url + ') ' + obj.statusText);
      console.error(JSON.stringify(error.json()));
    } else {
      console.error(JSON.stringify(error.json()));
    }

    let mens = JSON.parse(JSON.stringify(error.json()));
    //console.log(mens);
    this.title = mens.Estado;
    this.msg = mens.Mensaje;
    
    sessionStorage.setItem('msgTitle', mens.Estado);
    sessionStorage.setItem('msg', mens.Mensaje);
    sessionStorage.setItem('msgType', TipoMensaje.Error.toString());

    document.getElementById("openModalButton").click();
    
    return error;
  }

  ingresar() {
    const obj = {email: this.email, password: this.clave};
    let ruta: string = environment.backendRoute + 'login';

    this.xhr.httpPostS(ruta, obj, this.completado, this.error, () => {
      if (this.recordarme) {
        localStorage.setItem('token', sessionStorage.getItem('token'));
        sessionStorage.removeItem('token');
      }
      this.router.navigate(['/Principal']);
    });
    let inter = setTimeout(() => {
      this.msg = sessionStorage.getItem('msg');
      this.title = sessionStorage.getItem('msgTitle');
      this.tipo = TipoMensaje[sessionStorage.getItem('msgType')];
    }, 500);
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
