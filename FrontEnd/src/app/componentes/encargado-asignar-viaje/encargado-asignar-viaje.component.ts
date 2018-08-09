import { Component, OnInit } from '@angular/core';
import { ViajesService } from '../../servicios/viajes.service';
import { VehiculosService } from '../../servicios/vehiculos.service';
import { EmpleadosService } from '../../servicios/empleados.service';

import * as myGlobals from '../../clases/constantes';

@Component({
  selector: 'app-encargado-asignar-viaje',
  templateUrl: './encargado-asignar-viaje.component.html',
  styleUrls: ['./encargado-asignar-viaje.component.css']
})
export class EncargadoAsignarViajeComponent implements OnInit {

	miServicioEmpleados:EmpleadosService;
	miServicioDeViajes:ViajesService;
	miServicioVehiculos:VehiculosService;

	public viajes:any[];
	public vehiculos:any[];
	public remiserosDisponibles:any[];

  	constructor(servicioViajes:ViajesService, empleasdosService:EmpleadosService, vehiculosService: VehiculosService ) { 
  		this.miServicioEmpleados = empleasdosService; 
  		this.miServicioDeViajes = servicioViajes; 
  		this.miServicioVehiculos = vehiculosService; 
  	}

  	ngOnInit() {

		this.traerVehiculos();

  	}

  	/*traerRemiserosDisponibles(){

		this.miServicioEmpleados.traerRemiserosDisponibles().then(datos=>{

			this.remiserosDisponibles = datos;

			this.traerVehiculos();

	    });

  	}*/


  	traerVehiculos(){

		this.miServicioVehiculos.listar().then(datos=>{

			this.vehiculos = datos;

			this.traerViajes();
			
	    });


  	}


  	traerViajes(){

		this.miServicioDeViajes.listar().then(datos=>{

			this.viajes = datos.filter( viaje => viaje.estado_viaje === "Solicitado" && viaje.id_chofer === null );

			this.cargarRemiserosDisponibles();
			this.filtrarRemiserosAptos();

	    });

  	}

  	cargarRemiserosDisponibles(){

		this.miServicioEmpleados.listar().then(datos=>{

			this.remiserosDisponibles = datos.filter( emp => emp.perfil === "remisero" ).map( emp => emp.email );

	    });

  	}

  	filtrarRemiserosAptos(){

		let vehiculosAux: any = this.vehiculos;

	    this.viajes.forEach(function(viaje) { 

	    	if(
				viaje.cantidad_de_ascientos_solicitados === "N/A" &&
				viaje.comodidad_solicitada === "N/A"
	    	){

	    		viaje.RemiserosAptos = vehiculosAux.map( 
    				veh2 => veh2.remisero
		    	).filter(function(item, pos, self) {
				    return self.indexOf(item) == pos;
				});

	    	} else if(

				viaje.cantidad_de_ascientos_solicitados === "N/A" &&
				viaje.comodidad_solicitada !== "N/A"

	    	){

		    	viaje.RemiserosAptos = vehiculosAux.filter( 
		    		veh =>  viaje.comodidad_solicitada === veh.nivel_comodidad
		    	).map( 
		    		veh2 => veh2.remisero 
		    	).filter(function(item, pos, self) {
				    return self.indexOf(item) == pos;
				});

	    	} else if(

				viaje.cantidad_de_ascientos_solicitados !== "N/A" &&
				viaje.comodidad_solicitada === "N/A"

	    	){

		    	viaje.RemiserosAptos = vehiculosAux.filter( 
		    		veh =>  viaje.cantidad_de_ascientos_solicitados === veh.ascientos_disponibles
		    	).map( 
		    		veh2 => veh2.remisero 
		    	).filter(function(item, pos, self) {
				    return self.indexOf(item) == pos;
				});

	    	} else if(

				viaje.cantidad_de_ascientos_solicitados !== "N/A" &&
				viaje.comodidad_solicitada !== "N/A"

	    	){

		    	viaje.RemiserosAptos = vehiculosAux.filter( 
		    		veh =>  viaje.cantidad_de_ascientos_solicitados === veh.ascientos_disponibles &&
		    				viaje.comodidad_solicitada === veh.nivel_comodidad
		    	).map( 
		    		veh2 => veh2.remisero 
		    	).filter(function(item, pos, self) {
				    return self.indexOf(item) == pos;
				});

	    	}

	    });

  	}

  	onChange(id){

  		$("#btnModif"+id).prop("disabled", false);

  	}

  	asignarChofer(viaje){

	    var id = viaje.id;
	    var estado_viaje = viaje.estado_viaje;
	    var id_chofer = viaje.id_chofer;
	    var id_cliente = viaje.id_cliente;
	    var fecha_hora_viaje = viaje.fecha_hora_viaje;
	    var origen = viaje.origen;
	    var destino = viaje.destino;
	    var medio_de_pago = viaje.medio_de_pago;
	    var comodidad_solicitada = viaje.comodidad_solicitada;
	    var cantidad_de_ascientos_solicitados = viaje.cantidad_de_ascientos_solicitados;
	    var costo = viaje.costo;

		var datos = "id=" + id + 
		          "&estado_viaje=" + estado_viaje +
		          "&id_chofer=" + $("#id_chofer"+id).val() +
		          "&id_cliente=" + id_cliente +
		          "&fecha_hora_viaje=" + fecha_hora_viaje +
		          "&origen=" + origen +
		          "&destino=" + destino +
		          "&medio_de_pago=" + medio_de_pago +
		          "&comodidad_solicitada=" + comodidad_solicitada +
		          "&cantidad_de_ascientos_solicitados=" + cantidad_de_ascientos_solicitados +
		          "&costo=" + costo;

		$("#carga").html(myGlobals.LOADING_GIF);

		this.miServicioDeViajes.modificar(datos, 'application/x-www-form-urlencoded')
		.then( response => {

	  		if( response.hasOwnProperty('Estado') && response.Estado === "Error"){

	  		    //$("#myModalLabel").css("visibility", "hidden");
	  		    //$("#userErrorTitle").css("visibility", "visible");
	  		    //$("#userErrorBody").css("visibility", "visible");

	  		} else {
	          	$('#myModalLabelBody').html('Viaje modificado con exito');
	  		    $('#modalFelicidadesLogged').modal('show');
	  		    this.traerVehiculos();
	  		}

	  		$("#carga").html("");

		});

  	}

}
