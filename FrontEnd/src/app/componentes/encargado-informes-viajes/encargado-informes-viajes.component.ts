import { Component, OnInit } from '@angular/core';
import { EncuestasService } from '../../servicios/encuestas.service'; 

@Component({
  selector: 'app-encargado-informes-viajes',
  templateUrl: './encargado-informes-viajes.component.html',
  styleUrls: ['./encargado-informes-viajes.component.css']
})
export class EncargadoInformesViajesComponent implements OnInit {

	calificacion_servicio: Object;
	comportamiento_conductor: Object;
	limpieza_vehiculo:Object;
	duracion_viaje:Object;

	miServicioDeEncuestas:EncuestasService;

	private misEncuestas:any[];

	constructor(servicioEncuestas:EncuestasService) {

		this.miServicioDeEncuestas = servicioEncuestas;

	}


	ngOnInit() {

		this.cargarEncuestas();


	}

	cargarEncuestas(){

		this.miServicioDeEncuestas.listar().then(datos=>{

			this.misEncuestas = datos;

			this.generarGrafico();
			
	    });

	}

	generarGrafico(){

		/* CALIFICACION DEL SERVICIO */

		this.calificacionDelServicio();

		/* COMPORTAMIENTO DEL CONDUCTOR */

		this.comportamientoDelConductor();

		/* LIMPIEZA DE VEHICULO */

		this.limpiezaVehiculo();

		/* DURACION DEL VIAJE */

		this.duracionViaje();

	}

	dataGenerator(arr) {
	    var a = [], b = [], prev;

	    arr.sort();
	    for ( var i = 0; i < arr.length; i++ ) {
	        if ( arr[i] !== prev ) {
	            a.push(arr[i]);
	            b.push(1);
	        } else {
	            b[b.length-1]++;
	        }
	        prev = arr[i];
	    }

	    return [a, b];
	}

	calificacionDelServicio(){

		let data: any[] = this.dataGenerator( this.misEncuestas.map( enc => enc.calificacion_servicio ) );

		let series: any[] = [];

		data[0].forEach(function(element, index){
			series.push({
				name: data[0][index],
				y: data[1][index]
			});
		});

		this.calificacion_servicio = {
		    chart: {
		        plotBackgroundColor: null,
		        plotBorderWidth: null,
		        plotShadow: false,
		        type: 'pie'
		    },
		    title: {
		        text: 'Calificacion general del servicio'
		    },
		    tooltip: {
		        pointFormat: '{series.name}'
		    },
		    plotOptions: {
		        pie: {
		            allowPointSelect: true,
		            cursor: 'pointer',
		            dataLabels: {
		                enabled: true,
		                format: '<b>{point.name}</b>: {point.percentage:.1f} %',
		            }
		        }
		    },
		    series: [{
		        name: 'Brands',
		        colorByPoint: true,
		        data: series
		    }]
		};

	}

	comportamientoDelConductor(){

		let data: any[] = this.dataGenerator( this.misEncuestas.map( enc => enc.comportamiento_conductor ) );

		let series: any[] = [];

		data[0].forEach(function(element, index){
			series.push({
				name: data[0][index],
				y: data[1][index]
			});
		});

		this.comportamiento_conductor = {
		    chart: {
		        plotBackgroundColor: null,
		        plotBorderWidth: null,
		        plotShadow: false,
		        type: 'pie'
		    },
		    title: {
		        text: 'Comportamiento de los conductores'
		    },
		    tooltip: {
		        pointFormat: '{series.name}'
		    },
		    plotOptions: {
		        pie: {
		            allowPointSelect: true,
		            cursor: 'pointer',
		            dataLabels: {
		                enabled: true,
		                format: '<b>{point.name}</b>: {point.percentage:.1f} %',
		            }
		        }
		    },
		    series: [{
		        name: 'Brands',
		        colorByPoint: true,
		        data: series
		    }]
		};

	}

	limpiezaVehiculo(){
		
		let data: any[] = this.dataGenerator( this.misEncuestas.map( enc => enc.limpieza_vehiculo ) );

		let series: any[] = [];

		data[0].forEach(function(element, index){
			series.push({
				name: data[0][index],
				y: data[1][index]
			});
		});

		this.limpieza_vehiculo = {
		    chart: {
		        plotBackgroundColor: null,
		        plotBorderWidth: null,
		        plotShadow: false,
		        type: 'pie'
		    },
		    title: {
		        text: 'Calificacion de la limpieza de los vehiculos'
		    },
		    tooltip: {
		        pointFormat: '{series.name}'
		    },
		    plotOptions: {
		        pie: {
		            allowPointSelect: true,
		            cursor: 'pointer',
		            dataLabels: {
		                enabled: true,
		                format: '<b>{point.name}</b>: {point.percentage:.1f} %',
		            }
		        }
		    },
		    series: [{
		        name: 'Brands',
		        colorByPoint: true,
		        data: series
		    }]
		};

	}

	duracionViaje(){
		
		let data: any[] = this.dataGenerator( this.misEncuestas.map( enc => enc.duracion_viaje ) );

		let series: any[] = [];

		data[0].forEach(function(element, index){
			series.push({
				name: data[0][index],
				y: data[1][index]
			});
		});

		this.duracion_viaje = {
		    chart: {
		        plotBackgroundColor: null,
		        plotBorderWidth: null,
		        plotShadow: false,
		        type: 'pie'
		    },
		    title: {
		        text: 'Calificacion de la duracion de los viajes'
		    },
		    tooltip: {
		        pointFormat: '{series.name}'
		    },
		    plotOptions: {
		        pie: {
		            allowPointSelect: true,
		            cursor: 'pointer',
		            dataLabels: {
		                enabled: true,
		                format: '<b>{point.name}</b>: {point.percentage:.1f} %',
		            }
		        }
		    },
		    series: [{
		        name: 'Brands',
		        colorByPoint: true,
		        data: series
		    }]
		};

	}

}
