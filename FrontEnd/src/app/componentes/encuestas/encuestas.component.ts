import { Component, OnInit } from '@angular/core';
import { EncuestasService } from '../../servicios/encuestas.service'; 
import { Router, ActivatedRoute, ParamMap } from '@angular/router';
import { HttpHeaders, HttpClientModule, HttpClient } from '@angular/common/http';

import { UploadEvent, UploadFile, FileSystemFileEntry, FileSystemDirectoryEntry } from 'ngx-file-drop';

import * as myGlobals from '../../clases/constantes';

@Component({
  selector: 'app-encuestas',
  templateUrl: './encuestas.component.html',
  styleUrls: ['./encuestas.component.css']
})
export class EncuestasComponent implements OnInit {

  public files: UploadFile[] = [];

  idViaje: number;
  private sub: any;

  miServicioDeEncuestas:EncuestasService;

  constructor(private route: ActivatedRoute, private router: Router, servicioEncuestas:EncuestasService, private http: HttpClient) { 
  	this.miServicioDeEncuestas = servicioEncuestas; 
  }

  ngOnInit() {

    this.sub = this.route.params.subscribe(params => {

       this.idViaje = +params['idViaje'];

    });

  }

  ngOnDestroy() {
    this.sub.unsubscribe();
  }

  enviar(){

    if(this.files.length == 0){

      var foto01 = "placeholder";
      var foto02 = "placeholder";
      var foto03 = "placeholder";

    }
    if(this.files.length == 1){

      var foto01 = this.idViaje + "_foto01";
      var foto02 = "placeholder";
      var foto03 = "placeholder";

    }
    if(this.files.length == 2){

      var foto01 = this.idViaje + "_foto01";
      var foto02 = this.idViaje + "_foto02";
      var foto03 = "placeholder";

    }
    if(this.files.length == 3){

      var foto01 = this.idViaje + "_foto01";
      var foto02 = this.idViaje + "_foto02";
      var foto03 = this.idViaje + "_foto03";

    }

    var comportamiento_conductor = $('input[name="comportamiento_conductor"]:checked').val();
    var conversacion_conductor = $('input[name="conversacion_conductor"]:checked').val();
    var puntualidad_conductor = $('input[name="puntualidad_conductor"]:checked').val();
    var limpieza_vehiculo = $("#limpieza_vehiculo").val();
    var estado_vehiculo = $("#estado_vehiculo").val();
    var duracion_viaje = $("#duracion_viaje").val();
    var calificacion_servicio = $("#calificacion_servicio").val();

    if($("#recomendaria_servicio").prop('checked'))
      var recomendaria_servicio = 1;
    else
      var recomendaria_servicio = 0;

    var viaje = this.idViaje;
    var comentario = $("#comentario").val(); 
    
    if(
        comportamiento_conductor === "" || 
        conversacion_conductor === "" || 
        puntualidad_conductor === "" || 
        limpieza_vehiculo === "" || 
        estado_vehiculo === "" || 
        duracion_viaje === "" || 
        calificacion_servicio === "" ||
        comportamiento_conductor === ""
      ){

      alert("Faltan datos");

    } else {

      var datos = "comportamiento_conductor=" + comportamiento_conductor + 
                  "&conversacion_conductor=" + conversacion_conductor +
                  "&puntualidad_conductor=" + puntualidad_conductor +
                  "&limpieza_vehiculo=" + limpieza_vehiculo +
                  "&estado_vehiculo=" + estado_vehiculo +
                  "&duracion_viaje=" + duracion_viaje +
                  "&calificacion_servicio=" + calificacion_servicio + 
                  "&recomendaria_servicio=" + recomendaria_servicio + 
                  "&foto01=" + foto01 + 
                  "&foto02=" + foto02 + 
                  "&foto03=" + foto03 + 
                  "&viaje=" + viaje + 
                  "&comentario=" + comentario;

      $("#carga").html(myGlobals.LOADING_GIF);

      this.miServicioDeEncuestas.nuevo(datos, 'application/x-www-form-urlencoded')
      .then( response => {

          if( response.hasOwnProperty('Estado') && response.Estado === "Error"){

            $("#userError").css("visibility", "visible");

          } else {

            $("#formContent").css("display", "none");
            $("#signUpSucess").css("display", "inherit");

          }

          $("#carga").html("");

      });

      if(this.files.length != 0){
        this.uploadImages();
      }
      
    }

  }

  cancelar(){

  	this.router.navigate(["/Cliente/MisViajes"]);

  }

  test(){
  	$("#comportamiento_conductor_3").prop('checked', true);
  	$("#conversacion_conductor_4").prop('checked', true);
  	$("#puntualidad_conductor_3").prop('checked', true);
  	$("#limpieza_vehiculo").val("Buena");
  	$("#estado_vehiculo").val("Muy buena");
  	$("#duracion_viaje").val("Muy buena");
  	$("#calificacion_servicio").val("Muy buena");
  	$("#recomendaria_servicio").prop('checked', true);
  	$("#comentario").val("Comentario test");
  }

  public dropped(event: UploadEvent){

    for (const a of event.files) {

      if(this.files.length < 3){
        this.files.push(a);  
      }
      
    }

  }

  uploadImages(){

    var parentScope:any = this;

    this.files.forEach(function(droppedFile, index){

      // Is it a file?
      if (droppedFile.fileEntry.isFile) {

        const fileEntry = droppedFile.fileEntry as FileSystemFileEntry;
        
        fileEntry.file((file: File) => {

          // Here you can access the real file
          //console.log(droppedFile.relativePath, file);

          // You could upload it like this:
          const formData = new FormData();

          if(index === 0){
            formData.append('upfile', file, parentScope.idViaje + "_foto01.jpg");
          } 
          if(index === 1){
            formData.append('upfile', file, parentScope.idViaje + "_foto02.jpg");
          }
          if(index === 2){
            formData.append('upfile', file, parentScope.idViaje + "_foto03.jpg");
          }

          // Headers
          const headers = new HttpHeaders({
            'enctype': 'multipart/form-data'
          });

          parentScope.http.post(myGlobals.SERVER + '/encuestas/archivos', formData, { headers: headers })
          .subscribe(data => {
            
            console.log(data);

          });

        });

      } else {

        // It was a directory (empty directories are added, otherwise only files)
        const fileEntry = droppedFile.fileEntry as FileSystemDirectoryEntry;
        //console.log(droppedFile.relativePath, fileEntry);

      }

    });

  }

}
