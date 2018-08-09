import { Injectable } from '@angular/core';
import { MiHttpService } from './mi-http/mi-http.service';

@Injectable()
export class EncuestasService {

  constructor(public miHttp: MiHttpService) { }

  	public listar():Promise<Array<any>> {
		return this.miHttp.httpGetP("/encuestas")
			.then( data => {

				return data;

			})
			.catch( err => {

				return null;

			})
	}

  	public nuevo(params, contentType):Promise<any> {
       return this.miHttp.httpPostData("/encuestas", params, contentType)
          .then( data => {
            return data;
          })
          .catch( err => {
            console.log( err );
            return null;
          });
    }

    public nuevaImagen(params, contentType):Promise<any> {
       return this.miHttp.httpPostData("/encuestas/archivos", params, contentType)
          .then( data => {
            return data;
          })
          .catch( err => {
            console.log( err );
            return null;
          });
    }

}
