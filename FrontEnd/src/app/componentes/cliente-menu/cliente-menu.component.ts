import { Component, OnInit } from '@angular/core';
import { Router, ActivatedRoute, ParamMap } from '@angular/router';

@Component({
  selector: 'app-cliente-menu',
  templateUrl: './cliente-menu.component.html',
  styleUrls: ['./cliente-menu.component.css']
})
export class ClienteMenuComponent implements OnInit {

  constructor(private router: Router) { }

  ngOnInit() {
  }


  NuevoViaje(){
  	this.router.navigate(["/Cliente/NuevoViaje"]); 
  }

  MisViajes(){
  	this.router.navigate(["/Cliente/MisViajes"]); 
  }

}
