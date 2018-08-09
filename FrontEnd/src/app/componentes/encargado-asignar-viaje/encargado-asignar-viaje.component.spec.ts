import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { EncargadoAsignarViajeComponent } from './encargado-asignar-viaje.component';

describe('EncargadoAsignarViajeComponent', () => {
  let component: EncargadoAsignarViajeComponent;
  let fixture: ComponentFixture<EncargadoAsignarViajeComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ EncargadoAsignarViajeComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(EncargadoAsignarViajeComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
