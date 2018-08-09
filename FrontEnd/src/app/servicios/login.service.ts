import { Injectable } from '@angular/core';
import { MiHttpService } from './mi-http/mi-http.service'; 

@Injectable()
export class LoginService {

  constructor( public miHttp: MiHttpService ) { }

  public nuevo(params, contentType):Promise<any> {
       return this.miHttp.httpPostData("/login", params, contentType)
          .then( data => {
            return data;
          })
          .catch( err => {
            console.log( err );
            return null;
          });
    }

}
