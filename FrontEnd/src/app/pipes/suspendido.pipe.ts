import { Pipe, PipeTransform } from '@angular/core';

@Pipe({
  name: 'suspendido'
})
export class SuspendidoPipe implements PipeTransform {

  transform(value: any, args?: any): any {
    if(value==0){
    	return "No"
    } else {
    	return "Si";
    }
  }

}
