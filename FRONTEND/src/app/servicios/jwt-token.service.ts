import { Injectable } from '@angular/core';
import * as jwt_decode from "jwt-decode";

@Injectable()
export class JwtTokenService {

  constructor() { 

  }

  public decode(token: string): any {
    try {
      return jwt_decode(token);
    }
    catch(error) {
      console.error(error);
      return null;
    }
  }
}
