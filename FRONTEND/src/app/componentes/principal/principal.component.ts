import { Component, OnInit } from '@angular/core';
import { JwtTokenService } from '../../servicios/jwt-token.service';


@Component({
  selector: 'app-principal',
  templateUrl: './principal.component.html',
  styleUrls: ['./principal.component.css']
})
export class PrincipalComponent implements OnInit {
 public status: any = {
    isFirstOpen: true,
    isFirstDisabled: false
  };
  private jwtObj: any;
  private logedOn: string;

  constructor(
    private servicioJWT: JwtTokenService
  ) { 
    let token: string = (sessionStorage.getItem('token') ? sessionStorage.getItem('token') : localStorage.getItem('token'));
    this.jwtObj = this.servicioJWT.decode(token);
    this.logedOn = this.jwtObj.data.perfil;
  }

  ngOnInit() {
    //alert('Bienvenido '+this.logedOn);
    
  }
 

}
