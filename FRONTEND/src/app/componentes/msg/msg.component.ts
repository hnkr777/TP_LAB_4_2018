import { Component, OnInit, Input } from '@angular/core';

@Component({
  selector: 'msg-modal',
  templateUrl: './msg.component.html',
  styleUrls: ['./msg.component.css']
})
export class MsgComponent implements OnInit {
  @Input() titulo: string;
  @Input() mensaje: string;
  @Input() tipo: TipoMensaje;

  title: string;
  msg: string;

  constructor() {
    //this.mensaje = sessionStorage.getItem('msg');
  }
  
  ngOnInit() {
    //this.mensaje = sessionStorage.getItem('msg');
  }

}

export enum TipoMensaje {
  'Error',
  'Aviso',
  'Información'
}

/*import { Component, ElementRef, Input, OnInit, OnDestroy } from '@angular/core';
import * as $ from 'jquery';
 
import { ModalService } from '../servicios/';
 
@Component({
    moduleId: module.id.toString(),
    selector: 'modal',
    template: '<ng-content></ng-content>'
})
 
export class MsgComponent implements OnInit, OnDestroy {
    @Input() id: string;
    private element: JQuery;
 
    constructor(private modalService: ModalService, private el: ElementRef) {
        this.element = $(el.nativeElement);
    }
 
    ngOnInit(): void {
        let modal = this;
 
        // ensure id attribute exists
        if (!this.id) {
            console.error('modal must have an id');
            return;
        }
 
        // move element to bottom of page (just before </body>) so it can be displayed above everything else
        this.element.appendTo('body');
 
        // close modal on background click
        this.element.on('click', function (e: any) {
            var target = $(e.target);
            if (!target.closest('.modal-body').length) {
                modal.close();
            }
        });
 
        // add self (this modal instance) to the modal service so it's accessible from controllers
        this.modalService.add(this);
    }
 
    // remove self from modal service when directive is destroyed
    ngOnDestroy(): void {
        this.modalService.remove(this.id);
        this.element.remove();
    }
 
    // open modal
    open(): void {
        this.element.show();
        $('body').addClass('modal-open');
    }
 
    // close modal
    close(): void {
        this.element.hide();
        $('body').removeClass('modal-open');
    }
}*/