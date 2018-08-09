import { Component, OnInit } from '@angular/core';
import { Router, ActivatedRoute, ParamMap } from '@angular/router';

@Component({
  selector: 'app-encargado-menu',
  templateUrl: './encargado-menu.component.html',
  styleUrls: ['./encargado-menu.component.css']
})
export class EncargadoMenuComponent implements OnInit {

	constructor(private router: Router) { }

	ngOnInit() {
	}

	AsignarViaje(){
		this.router.navigate(["/Encargado/AsignarViaje"]); 
	}

	HistorialViajes(){
		this.router.navigate(["/Encargado/HistorialViajes"]); 
	}

	Informes(){
		this.router.navigate(["/Encargado/InformesViajes"]); 
	}

	Gestion(){
		this.router.navigate(["/Encargado/Gestion"]); 
	}

}
