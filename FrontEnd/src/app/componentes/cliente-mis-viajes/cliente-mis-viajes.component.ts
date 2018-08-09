import { Component, OnInit, ElementRef, NgZone, ViewChild } from '@angular/core';
import { FormControl } from '@angular/forms';
import { ViajesService } from '../../servicios/viajes.service'; 
import { EncuestasService } from '../../servicios/encuestas.service'; 
import { Router, ActivatedRoute, ParamMap } from '@angular/router';

import * as myGlobals from '../../clases/constantes';

//import { } from 'googlemaps';
import { MapsAPILoader } from '@agm/core';

@Component({
  selector: 'app-cliente-mis-viajes',
  templateUrl: './cliente-mis-viajes.component.html',
  styleUrls: ['./cliente-mis-viajes.component.css']
})
export class ClienteMisViajesComponent implements OnInit {

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

    public viaje:any;
    public misEncuestas:any[];
    public misEncuestasViajesId:any[];
  	public misViajes:any[];

  	miServicioDeViajes:ViajesService;
    miServicioDeEncuestas:EncuestasService;

  	constructor(servicioViajes:ViajesService, servicioEncuestas:EncuestasService, private router: Router, private mapsAPILoader: MapsAPILoader, private ngZone: NgZone) { 
  		this.miServicioDeViajes = servicioViajes; 
      this.miServicioDeEncuestas = servicioEncuestas; 
  	}

  	ngOnInit() {

      this.cargarDatosPagina();

      $("#id_cliente").val(localStorage.getItem("usuarioLogeado"));
      //create search FormControl
      this.searchControl = new FormControl();

      //set current position
      //this.setCurrentPosition();

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

    modificar(viaje){

      this.viaje = viaje;

      $("#id01").css("display","inherit");
      $("#id_cliente").val(this.viaje.id_cliente);
      $("#fecha").val(this.viaje.fecha_hora_viaje.split(" ")[0]);
      $("#hora").val(this.viaje.fecha_hora_viaje.split(" ")[1]);
      $("#origen").val(this.viaje.origen);
      $("#destino").val(this.viaje.destino);
      $("#medio_de_pago").val(this.viaje.medio_de_pago);
      $("#comodidad_solicitada").val(this.viaje.comodidad_solicitada);
      $("#cantidad_de_ascientos_solicitados").val(this.viaje.cantidad_de_ascientos_solicitados);

    }

    cancelar(viaje){

      this.viaje = viaje;

      var datos = "id=" + this.viaje.id + 
      "&estado_viaje=Cancelado" + 
      "&id_chofer=" + this.viaje.id_chofer + 
      "&id_cliente=" + this.viaje.id_cliente + 
      "&fecha_hora_viaje=" + this.viaje.fecha_hora_viaje +
      "&origen=" + this.viaje.origen +
      "&destino=" + this.viaje.destino +
      "&medio_de_pago=" + this.viaje.medio_de_pago +
      "&comodidad_solicitada=" + this.viaje.comodidad_solicitada +
      "&cantidad_de_ascientos_solicitados=" + this.viaje.cantidad_de_ascientos_solicitados +
      "&costo=" + this.viaje.costo; 

      $("#carga").html(myGlobals.LOADING_GIF);

      this.miServicioDeViajes.modificar(datos, 'application/x-www-form-urlencoded')
      .then( response => {

          if( response.hasOwnProperty('Estado') && response.Estado === "Error"){

            $("#userError").css("visibility", "visible");

          } else {

            $("#id01").css("display","inherit");
            $("#formContent").css("display", "none");
            $("#signUpSucessMsj").html("Viaje cancelado con exito!");   
            $("#signUpSucess").css("display", "inherit");

            this.cargarDatosPagina();

          }

          $("#carga").html("");

      });

    }

    modificarForm(){

      this.viaje.id_cliente = $("#id_cliente").val();
      this.viaje.fecha_hora_viaje = $("#fecha").val() + " " + $("#hora").val();
      this.viaje.origen = $("#origen").val();
      this.viaje.destino = $("#destino").val();
      this.viaje.medio_de_pago = $("#medio_de_pago").val();
      this.viaje.comodidad_solicitada = $("#comodidad_solicitada").val();
      this.viaje.cantidad_de_ascientos_solicitados = $("#cantidad_de_ascientos_solicitados").val();

      if(this.viaje.comodidad_solicitada == "Bajo"){
        this.viaje.costo = this.costo + 50;
      } else if (this.viaje.comodidad_solicitada == "Medio"){
         this.viaje.costo = this.costo + 100;
      } else if (this.viaje.comodidad_solicitada == "Alto"){
         this.viaje.costo = this.costo + 150;
      } else if (this.viaje.comodidad_solicitada == "N/A"){
         this.viaje.costo = this.costo;
      }

      this.costoFinal = this.viaje.costo;

      var datos = "id=" + this.viaje.id + 
      "&estado_viaje=" + this.viaje.estado_viaje + 
      "&id_chofer=" + this.viaje.id_chofer + 
      "&id_cliente=" + this.viaje.id_cliente + 
      "&fecha_hora_viaje=" + this.viaje.fecha_hora_viaje +
      "&origen=" + this.viaje.origen +
      "&destino=" + this.viaje.destino +
      "&medio_de_pago=" + this.viaje.medio_de_pago +
      "&comodidad_solicitada=" + this.viaje.comodidad_solicitada +
      "&cantidad_de_ascientos_solicitados=" + this.viaje.cantidad_de_ascientos_solicitados +
      "&costo=" + this.viaje.costo; 

      $("#carga").html(myGlobals.LOADING_GIF);

      this.miServicioDeViajes.modificar(datos, 'application/x-www-form-urlencoded')
      .then( response => {

          if( response.hasOwnProperty('Estado') && response.Estado === "Error"){

            $("#userError").css("visibility", "visible");

          } else {

            $("#costoMensaje").html("El costo final del viaje sera de " + (this.costoFinal).toFixed(2) + " $");
            $("#duracionMensaje").html("La duracion del viaje sera de " + this.durationText);

            $("#formContent").css("display", "none");
            $("#signUpSucessMsj").html("Viaje modificado con exito!");
            $("#signUpSucess").css("display", "inherit");

            this.cargarDatosPagina();

          }

          $("#carga").html("");

      });

    }

    cancelarForm(){
      document.getElementById('id01').style.display='none';
    }

    volverMisViajes(){

      $("#id01").css("display", "none");
      $("#formContent").css("display", "inherit");
      $("#signUpSucess").css("display", "none");

    }

    cargarTabla(){

      this.miServicioDeViajes.listar().then(datos=>{

        this.misViajes = datos.filter(viaje => viaje.id_cliente === localStorage.getItem("usuarioLogeado"));
        
      });

    }

    traerEncuestas(){

      this.miServicioDeEncuestas.listar().then(datos=>{

        this.misEncuestas = datos;

        this.misEncuestasViajesId = datos.map(encuesta => encuesta.viaje);

        this.cargarTabla();

      });

    }

    cargarDatosPagina(){

      this.traerEncuestas();

    }

    encuesta(idViaje){

      this.router.navigate(['/Encuestas', idViaje]);

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

    encuestaMostrarFotos(id){

      //src="http://localhost:8080/PHP/imagenesEncuestas/1_foto01.jpg" 

      let encuesta: any = (this.misEncuestas.filter(encuesta => encuesta.viaje === id))[0];

      if(encuesta.foto01 == "placeholder"){
        $("#foto01").attr("src","./assets/imagenes/noPic.jpg");
      } else {
        $("#foto01").attr("src",myGlobals.SERVER + "PHP/imagenesEncuestas/" + encuesta.foto01 + ".jpg");
      }

      if(encuesta.foto02 == "placeholder"){
        $("#foto02").attr("src","./assets/imagenes/noPic.jpg");
      } else {
        $("#foto02").attr("src",myGlobals.SERVER + "PHP/imagenesEncuestas/" + encuesta.foto02 + ".jpg");
      }

      if(encuesta.foto03 == "placeholder"){
        $("#foto03").attr("src","./assets/imagenes/noPic.jpg");
      } else {
        $("#foto03").attr("src",myGlobals.SERVER + "PHP/imagenesEncuestas/" + encuesta.foto03 + ".jpg");
      }

      $("#mainContainer").css("display","none");
      $("#fotosEncuesta").css("display","block");

    }

    volverImagenes(){
      $("#mainContainer").css("display","block");
      $("#fotosEncuesta").css("display","none");
    }

}
