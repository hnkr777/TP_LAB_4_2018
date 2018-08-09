import { CanActivate } from '@angular/router';
import { Injectable } from '@angular/core';
import { Router, ActivatedRoute, ParamMap } from '@angular/router';

import { JwtHelper } from 'angular2-jwt';

@Injectable()
export class PrincipalGuard implements CanActivate{

	constructor(private router: Router) {}

	canActivate() {

		let jwtHelper: JwtHelper = new JwtHelper();
		let token: any = localStorage.getItem("SessionToken");

		if(token !== null){

			if(!jwtHelper.isTokenExpired(token)){

				let datosUsuarioLogeado: any = jwtHelper.decodeToken(token);

				if(datosUsuarioLogeado.data.perfil === "encargado"){

					this.router.navigate(["/Encargado/Menu"]);

				} else if(datosUsuarioLogeado.data.perfil === "cliente"){

					this.router.navigate(["/Cliente/Menu"]);

				} else if(datosUsuarioLogeado.data.perfil === "remisero"){

					this.router.navigate(["/Remisero/Menu"]);

				} else {

					return true;

				}

			} else {

				return true;

			}

		} else {

			return true;		

		}

		return true;

	}

}
