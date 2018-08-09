import { Component, OnInit } from '@angular/core';
import { Router, ActivatedRoute, ParamMap } from '@angular/router';

@Component({
  selector: 'app-remisero',
  templateUrl: './remisero.component.html',
  styleUrls: ['./remisero.component.css']
})
export class RemiseroComponent implements OnInit {

  constructor(private router: Router) { }

  ngOnInit() {
  	this.router.navigate(["/Remisero/Menu"]);
  }

}
