import { Component, OnInit } from '@angular/core';
import { Router, ActivatedRoute, ParamMap } from '@angular/router';
import { ViajesService } from '../../servicios/viajes.service'; 

import * as myGlobals from '../../clases/constantes';


@Component({
  selector: 'app-remisero-mis-viajes',
  templateUrl: './remisero-mis-viajes.component.html',
  styleUrls: ['./remisero-mis-viajes.component.css']
})
export class RemiseroMisViajesComponent implements OnInit {

	public misViajes:any[];
	miServicioDeViajes:ViajesService;

	constructor(private router: Router, servicioViajes:ViajesService) { this.miServicioDeViajes = servicioViajes;  }

  	ngOnInit() {

  		this.cargarTabla();

  	}

  	modificar(viaje:any){

  		viaje.estado_viaje = $("#estado_viaje"+viaje.id).val();

		var datos = "id=" + viaje.id + 
			"&estado_viaje=" + viaje.estado_viaje + 
			"&id_chofer=" + viaje.id_chofer + 
			"&id_cliente=" + viaje.id_cliente + 
			"&fecha_hora_viaje=" + viaje.fecha_hora_viaje +
			"&origen=" + viaje.origen +
			"&destino=" + viaje.destino +
			"&medio_de_pago=" + viaje.medio_de_pago +
			"&comodidad_solicitada=" + viaje.comodidad_solicitada +
			"&cantidad_de_ascientos_solicitados=" + viaje.cantidad_de_ascientos_solicitados +
			"&costo=" + viaje.costo; 

		$("#carga").html(myGlobals.LOADING_GIF);

		this.miServicioDeViajes.modificar(datos, 'application/x-www-form-urlencoded')
		.then( response => {

			if( response.hasOwnProperty('Estado') && response.Estado === "Error"){

			    $("#myModalLabel").css("visibility", "hidden");
			    $("#userErrorTitle").css("visibility", "visible");
			    $("#userErrorBody").css("visibility", "visible");

			} else {

			    $('#modalFelicidadesLogged').modal('show');
			    this.cargarTabla();
			}

			$("#carga").html("");

		});

  	}

  	cargarTabla(){

		this.miServicioDeViajes.listar().then(datos=>{

			this.misViajes = datos.filter(viaje => viaje.id_chofer === localStorage.getItem("usuarioLogeado") && viaje.estado_viaje === 'Solicitado');
			
	    });

  	}

}
