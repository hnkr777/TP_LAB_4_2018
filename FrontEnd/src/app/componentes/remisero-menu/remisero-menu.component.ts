import { Component, OnInit } from '@angular/core';

import { Router, ActivatedRoute, ParamMap } from '@angular/router';

@Component({
  selector: 'app-remisero-menu',
  templateUrl: './remisero-menu.component.html',
  styleUrls: ['./remisero-menu.component.css']
})
export class RemiseroMenuComponent implements OnInit {

  constructor(private router: Router) { }

  ngOnInit() {
  }

   MisViajes(){
  	this.router.navigate(["/Remisero/MisViajes"]); 
  }

}
