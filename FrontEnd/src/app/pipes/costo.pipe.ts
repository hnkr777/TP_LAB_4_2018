import { Pipe, PipeTransform } from '@angular/core';

@Pipe({
  name: 'costo'
})
export class CostoPipe implements PipeTransform {

  transform(value: any, args?: any): any {
    if(value===null || value==="0.00")
    {
      return "N/A";
    } else {
      return "$" + value;
    }
  }

}
