import { Component, OnInit, ElementRef, NgZone, ViewChild } from '@angular/core';
import { FormControl } from '@angular/forms';
import { ViajesService } from '../../servicios/viajes.service';
import { Router, ActivatedRoute, ParamMap } from '@angular/router';

import * as myGlobals from '../../clases/constantes';

import { } from 'googlemaps';
import { MapsAPILoader } from '@agm/core';

@Component({
  selector: 'app-cliente-nuevo-viaje',
  templateUrl: './cliente-nuevo-viaje.component.html',
  styleUrls: ['./cliente-nuevo-viaje.component.css']
})
export class ClienteNuevoViajeComponent implements OnInit {

  //=====================GOOGLE MAPS====================//

  lat: number = -34.603722;
  lng: number = -58.381592;
  zoom: number = 11;

  //ORIGEN
  latOrigen: number;
  lngOrigen: number;

  //DESTINO
  latDestino: number;
  lngDestino: number;

  drivingOptions = {
    departureTime: new Date(Date.now()),  // for the time N milliseconds from now.
    trafficModel: 'optimistic'
  };

  dir: any = undefined;

  distanceText: string;
  distanceNumber: number;
  durationText: string;
  durationNumber: number;

  costo: number;
  costoFinal: number;

  public searchControl: FormControl;

  @ViewChild("origen")
  public searchElementRefOrigen: ElementRef;

  @ViewChild("destino")
  public searchElementRefDestino: ElementRef;

  //=====================GOOGLE MAPS====================//

  miServicioDeViajes:ViajesService;

  constructor(private router: Router, servicioViajes:ViajesService, private mapsAPILoader: MapsAPILoader, private ngZone: NgZone) { 
    this.miServicioDeViajes = servicioViajes;
  }

  ngOnInit() {

  	$("#id_cliente").val(localStorage.getItem("usuarioLogeado"));
    //create search FormControl
    this.searchControl = new FormControl();

    //load Places Autocomplete
    this.mapsAPILoader.load().then(() => {

      //ORIGEN
      let autocomplete = new google.maps.places.Autocomplete(this.searchElementRefOrigen.nativeElement, {
        types: ["address"]
      });
      autocomplete.addListener("place_changed", () => {
        this.ngZone.run(() => {
          //get the place result
          let place: google.maps.places.PlaceResult = autocomplete.getPlace();

          //verify result
          if (place.geometry === undefined || place.geometry === null) {
            return;
          }

          //set latitude, longitude and zoom
          this.latOrigen = place.geometry.location.lat();
          this.lngOrigen = place.geometry.location.lng();
          //this.zoom = 12;

          if(this.latDestino !== undefined && this.lngDestino !== undefined){
            this.marcarRuta();
          };

        });
      });

      //DESTINO
      let autocomplete2 = new google.maps.places.Autocomplete(this.searchElementRefDestino.nativeElement, {
        types: ["address"]
      });
      autocomplete2.addListener("place_changed", () => {
        this.ngZone.run(() => {
          //get the place result
          let place: google.maps.places.PlaceResult = autocomplete2.getPlace();

          //verify result
          if (place.geometry === undefined || place.geometry === null) {
            return;
          }

          //set latitude, longitude and zoom
          this.latDestino = place.geometry.location.lat();
          this.lngDestino = place.geometry.location.lng();
          //this.zoom = 12;

          if(this.latOrigen !== undefined && this.lngOrigen !== undefined){
            this.marcarRuta();
          };

        });
      });

    });

  }

  registrar(){

    var id_cliente = $("#id_cliente").val();
    var fecha_hora_viaje = $("#fecha").val() + " " + $("#hora").val();
    var origen = $("#origen").val();
    var destino = $("#destino").val();
    var medio_de_pago = $("#medio_de_pago").val();
    var comodidad_solicitada = $("#comodidad_solicitada").val();
    var cantidad_de_ascientos_solicitados = $("#cantidad_de_ascientos_solicitados").val();

    if(comodidad_solicitada == "Bajo"){
      var costo = this.costo + 50;
    } else if (comodidad_solicitada == "Medio"){
      var costo = this.costo + 100;
    } else if (comodidad_solicitada == "Alto"){
      var costo = this.costo + 150;
    } else if (comodidad_solicitada == "N/A"){
      var costo = this.costo;
    }

    this.costoFinal = costo;

    var numCaptcha = $("#numCaptcha").val();

    if(
        id_cliente === "" || 
        fecha_hora_viaje === "" || 
        origen === "" || 
        destino === "" || 
        medio_de_pago === "" || 
        comodidad_solicitada === "" || 
        cantidad_de_ascientos_solicitados === ""
      ){

        $('#mensaje').text('Falta completar campos');
        $('#mensaje').css('display', 'inline');
        $('#modalFelicidadesLogged').modal('show');
        //alert("Faltan datos");

    } else {

      if( numCaptcha != 11){

        //alert("Captcha erroneo");
        $('#modalFelicidadesLogged').modal('show');

      } else {

        var datos = "id_cliente=" + id_cliente + 
            "&fecha_hora_viaje=" + fecha_hora_viaje +
            "&origen=" + origen +
            "&destino=" + destino +
            "&medio_de_pago=" + medio_de_pago +
            "&comodidad_solicitada=" + comodidad_solicitada +
            "&cantidad_de_ascientos_solicitados=" + cantidad_de_ascientos_solicitados + 
            "&costo=" + costo;

        $("#carga").html(myGlobals.LOADING_GIF);

        this.miServicioDeViajes.nuevo(datos, 'application/x-www-form-urlencoded')
        .then( response => {

            if( response.hasOwnProperty('Estado') && response.Estado === "Error"){

              $("#userError").css("visibility", "visible");

            } else {

              $("#costoMensaje").html("El costo final del viaje sera de " + (this.costoFinal).toFixed(2) + " $");
              $("#duracionMensaje").html("La duracion del viaje sera de " + this.durationText);

              $("#formContent").css("display", "none");
              $("#signUpSucess").css("display", "inherit");

            }

            $("#carga").html("");

        });

      }

    }

  }

  cancelar(){
  	this.router.navigate(["/"]);
  }

  test(){
  	$("#fecha").val("2018-06-11");
  	$("#hora").val("14:54:46");
  	$("#origen").val("José Bonifacio 802, Buenos Aires, Argentina");
  	$("#destino").val("Diagonal Norte 650, Buenos Aires, Argentina");
  	$("#medio_de_pago").val("Efectivo");
  	$("#comodidad_solicitada").val("Alto");
  	$("#cantidad_de_ascientos_solicitados").val("N/A");
  }

  marcarRuta(){

    this.dir = {
      origin: $("#origen").val(),
      destination: $("#destino").val()
    }

  }

  dirChange(event:any){
    //console.log(event);

    var response: any = event.routes[0].legs[0];

    //console.log(response);

    this.distanceText = response.distance.text;
    this.distanceNumber = response.distance.value;
    this.durationText =  response.duration.text;
    this.durationNumber =  response.duration.value;

    this.costo = this.distanceNumber / 100;

    //console.log(this);
    
  }

}
