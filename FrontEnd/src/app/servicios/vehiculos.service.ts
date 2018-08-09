import { Injectable } from '@angular/core';
import { MiHttpService } from './mi-http/mi-http.service';

@Injectable()
export class VehiculosService {

  constructor(public miHttp: MiHttpService) { }

  	public listar():Promise<Array<any>> {
		return this.miHttp.httpGetP("/vehiculos")
			.then( data => {

				return data;

			})
			.catch( err => {

				return null;

			})
	}

  	public nuevo(params, contentType):Promise<any> {
       return this.miHttp.httpPostData("/vehiculos", params, contentType)
          .then( data => {
            return data;
          })
          .catch( err => {
            console.log( err );
            return null;
          });
    }

    public modificar(params, contentType):Promise<any> {
       return this.miHttp.httpPostData("/vehiculos/modificar", params, contentType)
          .then( data => {
            return data;
          })
          .catch( err => {
            console.log( err );
            return null;
          });
    }

}
