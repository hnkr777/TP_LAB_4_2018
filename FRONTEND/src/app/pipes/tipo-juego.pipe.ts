import { Pipe, PipeTransform } from '@angular/core';

@Pipe({
  name: 'tipoJuego',
  pure: false
})
export class TipoJuegoPipe implements PipeTransform {
  transform(items: any[], filter: any): any {
    if (!items || !filter) {
        return items;
    }

    return items.filter(item => item.juego.indexOf(filter.juego) !== -1);
  }

}
