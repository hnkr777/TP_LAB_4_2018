import { Injectable } from '@angular/core';
import { MiHttpService } from './mi-http/mi-http.service';

@Injectable()
export class ViajesService {

  constructor(public miHttp: MiHttpService) { }

	public listar():Promise<Array<any>> {
		return this.miHttp.httpGetP("/viajes")
			.then( data => {

				return data;

			})
			.catch( err => {

				return null;

			})
	}

  	public nuevo(params, contentType):Promise<any> {
       return this.miHttp.httpPostData("/viajes", params, contentType)
          .then( data => {
            return data;
          })
          .catch( err => {
            console.log( err );
            return null;
          });
    }

    public modificar(params, contentType):Promise<any> {
       return this.miHttp.httpPostData("/viajes/modificar", params, contentType)
          .then( data => {
            return data;
          })
          .catch( err => {
            console.log( err );
            return null;
          });
    }

}
