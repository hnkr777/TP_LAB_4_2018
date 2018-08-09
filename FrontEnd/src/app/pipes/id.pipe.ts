import { Pipe, PipeTransform } from '@angular/core';

@Pipe({
  name: 'id'
})
export class IdPipe implements PipeTransform {

  transform(value: any, args?: any): any {
    
    let digits: any = value.toString().length; 

    if(digits === 1)
		return "00000" + value;
    if(digits === 2)
		return "0000" + value;
    if(digits === 3)
		return "000" + value;

  }

}
