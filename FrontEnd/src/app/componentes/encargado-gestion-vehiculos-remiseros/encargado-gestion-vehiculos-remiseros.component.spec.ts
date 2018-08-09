import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { EncargadoGestionVehiculosRemiserosComponent } from './encargado-gestion-vehiculos-remiseros.component';

describe('EncargadoGestionVehiculosRemiserosComponent', () => {
  let component: EncargadoGestionVehiculosRemiserosComponent;
  let fixture: ComponentFixture<EncargadoGestionVehiculosRemiserosComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ EncargadoGestionVehiculosRemiserosComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(EncargadoGestionVehiculosRemiserosComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
