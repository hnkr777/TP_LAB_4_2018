import { Component, OnInit } from '@angular/core';
import { JwtTokenService } from '../../servicios/jwt-token.service';

@Component({
  selector: 'app-nuevo-viaje',
  templateUrl: './nuevo-viaje.component.html',
  styleUrls: ['./nuevo-viaje.component.css']
})
export class NuevoViajeComponent implements OnInit {
  private jwtObj: any;
  private logedOn: string;

  constructor(private servicioJWT: JwtTokenService) { 
    let token: string = (sessionStorage.getItem('token') ? sessionStorage.getItem('token') : localStorage.getItem('token'));
    this.jwtObj = this.servicioJWT.decode(token);
    this.logedOn = this.jwtObj.data.perfil;
  }

  ngOnInit() {
    
  }

}
