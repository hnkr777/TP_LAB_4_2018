import { Component, OnInit } from '@angular/core';
import { Router, ActivatedRoute, ParamMap } from '@angular/router';

@Component({
  selector: 'app-menu',
  templateUrl: './menu.component.html',
  styleUrls: ['./menu.component.css']
})
export class MenuComponent implements OnInit {

  constructor(private route: ActivatedRoute,
    private router: Router) { }

  ngOnInit() {
  }

  logout() {
    sessionStorage.removeItem('token');
    localStorage.removeItem('token');
    location.reload();
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
      case 'Principal':
        this.router.navigate(['/Principal']);
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
      default:
        this.router.navigate(['']);
      break;
    }
  }

}
