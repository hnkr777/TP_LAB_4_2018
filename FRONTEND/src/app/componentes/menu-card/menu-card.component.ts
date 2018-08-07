
import { Component, OnInit, AfterViewInit, Renderer2, Directive, HostListener, ElementRef } from '@angular/core';
import { Router, ActivatedRoute, ParamMap } from '@angular/router';

@Component({
  selector: 'app-menu-card',
  templateUrl: './menu-card.component.html',
  styleUrls: ['./menu-card.component.css']
})
/*@Directive({
  selector: '[scroll]'
})*/
export class MenuCardComponent implements OnInit, AfterViewInit {
  scrolled: boolean;

  constructor( private el: ElementRef, private renderer: Renderer2, private route: ActivatedRoute,
    private router: Router) { }

  ngOnInit() {}

  ngAfterViewInit() {
    this.scrolled = false;
  }

  @HostListener('mousewheel', ['$event']) onMousewheel(event) {
    this.sd_event_up(event.wheelDelta);
  }

  sd_event_up(delta: number) {
    if(delta < 0) {
      this.sd_event_down(null);
      this.scrolled = true;
    }
    if(delta >= 0) {
      if(this.scrolled && (window.scrollY == 0  || delta == 0) ) {
        this.renderer.removeClass(document.getElementsByClassName('content').item(0), "scrolled");
        //this.renderer.setStyle(document.getElementsByClassName('content').item(0), "display", "none");
        this.renderer.removeClass( document.getElementsByClassName('hero').item(0), "scrolled");
      }
    }
  }

  sd_event_down(event: any) {
    this.scrolled = true;
    this.renderer.addClass(document.getElementsByClassName('content').item(0), "scrolled");
    //this.renderer.setStyle(document.getElementsByClassName('content').item(0), "display", "flex");
    this.renderer.addClass( document.getElementsByClassName('hero').item(0), "scrolled");
  }

  Juego(tipo: string) {
    switch (tipo) {
      case 'Adivina':
          this.router.navigate(['/Juegos/Adivina']);
        break;
      case 'BlackJack':
          this.router.navigate(['/Juegos/BlackJack']);
        break;
      case 'Agilidad':
          this.router.navigate(['/Juegos/Agilidad']);
        break;
      case 'PPT':
          this.router.navigate(['/Juegos/PPT']);
        break;
      case 'Anagrama':
          this.router.navigate(['/Juegos/Anagrama']);
        break;
      case 'Tateti':
          this.router.navigate(['/Juegos/Tateti']);
        break;
      case 'AdivinaMasListado':
          this.router.navigate(['/Juegos/AdivinaMasListado']);
        break;
      case 'AgilidadaMasListado':
          this.router.navigate(['/Juegos/AgilidadaMasListado']);
        break;
    }
  }
}

