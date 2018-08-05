import { Component, OnInit } from '@angular/core';

@Component({
  selector: 'app-decorations',
  templateUrl: './decorations.component.html',
  styleUrls: ['./decorations.component.css']
})
export class DecorationsComponent implements OnInit {

  constructor() { }

  ngOnInit() {
    addEventListener("load", function() { 
      setTimeout(function () {
        window.scrollTo(0,1);
      }, 0);
    }, false);
  }
}
