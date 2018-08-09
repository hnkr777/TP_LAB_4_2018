import { Component, OnInit } from '@angular/core';
import { ViajesService } from '../../servicios/viajes.service'; 

@Component({
  selector: 'app-encargado-historial-viajes',
  templateUrl: './encargado-historial-viajes.component.html',
  styleUrls: ['./encargado-historial-viajes.component.css']
})
export class EncargadoHistorialViajesComponent implements OnInit {

	public misViajes:any[];
	miServicioDeViajes:ViajesService;

  	constructor(servicioViajes:ViajesService) { 
  		this.miServicioDeViajes = servicioViajes; 
  	}

  	ngOnInit() {

		this.miServicioDeViajes.listar().then(datos=>{

			this.misViajes = datos;
			
	    });

  	}

}
