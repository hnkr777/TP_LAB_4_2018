import { Component, OnInit } from '@angular/core';
import { VehiculosService } from '../../servicios/vehiculos.service';
import { EmpleadosService } from '../../servicios/empleados.service';

import * as myGlobals from '../../clases/constantes';

@Component({
  selector: 'app-encargado-gestion-vehiculos-remiseros',
  templateUrl: './encargado-gestion-vehiculos-remiseros.component.html',
  styleUrls: ['./encargado-gestion-vehiculos-remiseros.component.css']
})
export class EncargadoGestionVehiculosRemiserosComponent implements OnInit {

	miServicioEmpleados:EmpleadosService;
	miServicioVehiculos:VehiculosService;

	public empleados:any[];
	public vehiculos:any[];

  constructor( empleasdosService:EmpleadosService, vehiculosService: VehiculosService ) { 
  	this.miServicioVehiculos = vehiculosService;
  	this.miServicioEmpleados = empleasdosService;
  }

  ngOnInit() {

  	this.traerEmpleados();
  	this.traerVehiculos();

  }

  traerEmpleados(){

	this.miServicioEmpleados.listar().then(datos=>{

		this.empleados = datos.filter(empleado => empleado.perfil === "remisero");

    });

  }

  traerVehiculos(){

	this.miServicioVehiculos.listar().then(datos=>{

		this.vehiculos = datos;

    });

  }

  suspenderVehiculo(vehiculo){

  	if(vehiculo.suspendido == 0){

  		var datos = "id=" + vehiculo.id + 
  		"&remisero=" + vehiculo.remisero + 
  		"&nivel_comodidad=" + vehiculo.nivel_comodidad + 
  		"&ascientos_disponibles=" + vehiculo.ascientos_disponibles +
  		"&suspendido=" + "1"; 
  	
  	} else {

  		var datos = "id=" + vehiculo.id + 
  		"&remisero=" + vehiculo.remisero + 
  		"&nivel_comodidad=" + vehiculo.nivel_comodidad + 
  		"&ascientos_disponibles=" + vehiculo.ascientos_disponibles +
  		"&suspendido=" + "0"; 

  	}

  	$("#carga").html(myGlobals.LOADING_GIF);

  	this.miServicioVehiculos.modificar(datos, 'application/x-www-form-urlencoded')
  	.then( response => {

  		if( response.hasOwnProperty('Estado') && response.Estado === "Error"){

  		    $("#myModalLabel").css("visibility", "hidden");
  		    $("#userErrorTitle").css("visibility", "visible");
  		    $("#userErrorBody").css("visibility", "visible");

  		} else {
          $('#myModalLabelBody').html('Vehículo modificado con éxito');
  		    $('#modalFelicidadesLogged').modal('show');
  		    this.traerVehiculos();
  		}

  		$("#carga").html("");

  	});

  }

  suspenderRemisero(remisero){

    if(remisero.suspendido == 0){

      var datos = "id=" + remisero.id + 
      "&email=" + remisero.email + 
      "&password=" + remisero.password + 
      "&perfil=" + remisero.perfil +
      "&suspendido=" + "1"; 
    
    } else {

      var datos = "id=" + remisero.id + 
      "&email=" + remisero.email + 
      "&password=" + remisero.password + 
      "&perfil=" + remisero.perfil +
      "&suspendido=" + "0"; 

    }

    $("#carga").html(myGlobals.LOADING_GIF);

    this.miServicioEmpleados.modificar(datos, 'application/x-www-form-urlencoded')
    .then( response => {

      if( response.hasOwnProperty('Estado') && response.Estado === "Error"){

          $("#myModalLabel").css("visibility", "hidden");
          $("#userErrorTitle").css("visibility", "visible");
          $("#userErrorBody").css("visibility", "visible");

      } else {
          $('#myModalLabelBody').html('Remisero modificado con éxito');
          $('#modalFelicidadesLogged').modal('show');
          this.traerEmpleados();
      }

      $("#carga").html("");

    });

  }

  vehiculosShow(){

  	if( $("#vehiculosTable").css("display") === "none" ){
  		$("#vehiculosTable").css("display","table");
  	} else {
  		$("#vehiculosTable").css("display","none");
  	}

  }

  remiserosShow(){

  	if( $("#remiserosTable").css("display") === "none" ){
  		$("#remiserosTable").css("display","table");
  	} else {
  		$("#remiserosTable").css("display","none");
  	}

  }

  nuevoVehiculo(){

  	$("#mainMenu").css("display","none");
  	$("#mainNuevoVehiculo").css("display","block");

  }

  nuevoRemisero(){

    $("#mainMenu").css("display","none");
    $("#mainNuevoRemisero").css("display","block");

  }

  registrarVehiculo(){

    var datos = "remisero=" + $("#remisero").val() + 
    "&nivel_comodidad=" + $("#nivel_comodidad").val() + 
    "&ascientos_disponibles=" + $("#ascientos_disponibles").val(); 

    $("#carga").html(myGlobals.LOADING_GIF);

    this.miServicioVehiculos.nuevo(datos, 'application/x-www-form-urlencoded')
    .then( response => {

      if( response.hasOwnProperty('Estado') && response.Estado === "Error"){

          //$("#myModalLabel").css("visibility", "hidden");
          //$("#userErrorTitle").css("visibility", "visible");
          //$("#userErrorBody").css("visibility", "visible");

      } else {

          $('#myModalLabelBody').html('Vehiculo registrado con exito');
          $('#modalFelicidadesLogged').modal('show');
          this.traerVehiculos();

          $("#mainMenu").css("display","block");
          $("#mainNuevoVehiculo").css("display","none");

      }

      $("#carga").html("");
      
    });

  }

  registrarRemisero(){

    var datos = "email=" + $("#email").val() + 
    "&password=" + $("#password").val() + 
    "&perfil=remisero";

    $("#carga").html(myGlobals.LOADING_GIF);

    this.miServicioEmpleados.nuevo(datos, 'application/x-www-form-urlencoded')
    .then( response => {

      if( response.hasOwnProperty('Estado') && response.Estado === "Error"){

          //$("#myModalLabel").css("visibility", "hidden");
          //$("#userErrorTitle").css("visibility", "visible");
          //$("#userErrorBody").css("visibility", "visible");

      } else {

          $('#myModalLabelBody').html('Remisero registrado con exito');
          $('#modalFelicidadesLogged').modal('show');
          this.traerEmpleados();

          $("#mainMenu").css("display","block");
          $("#mainNuevoRemisero").css("display","none");

      }

      $("#carga").html("");
      
    });

  }

}
