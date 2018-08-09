import { Injectable } from '@angular/core';
import { MiHttpService } from './mi-http/mi-http.service';

@Injectable()
export class EmpleadosService {

  constructor(public miHttp: MiHttpService) { }

  	public listar():Promise<Array<any>> {
		return this.miHttp.httpGetP("/empleados")
			.then( data => {

				return data;

			})
			.catch( err => {

				return null;

			})
	}

  	public nuevo(params: any, contentType?: any):Promise<any> {
      if(contentType===undefined) {
        contentType = 'application/json';
      }
      
      return this.miHttp.httpPostData("/empleados", params, contentType);
          /*.then( data => {
            return data;
          })
          .catch( err => {
            console.log( err );
            return err;
          });*/
    }

    public modificar(params, contentType):Promise<any> {
       return this.miHttp.httpPostData("/empleados/modificar", params, contentType)
          .then( data => {
            return data;
          })
          .catch( err => {
            console.log( err );
            return null;
          });
    }

    public traerRemiserosDisponibles():Promise<Array<any>> {
    return this.miHttp.httpGetP("/empleados/remiserosDisponibles")
      .then( data => {

        return data;

      })
      .catch( err => {

        return null;

      })
  }

}
