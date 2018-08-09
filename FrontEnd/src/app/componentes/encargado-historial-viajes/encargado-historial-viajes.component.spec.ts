import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { EncargadoHistorialViajesComponent } from './encargado-historial-viajes.component';

describe('EncargadoHistorialViajesComponent', () => {
  let component: EncargadoHistorialViajesComponent;
  let fixture: ComponentFixture<EncargadoHistorialViajesComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ EncargadoHistorialViajesComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(EncargadoHistorialViajesComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
