import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { EncargadoInformesViajesComponent } from './encargado-informes-viajes.component';

describe('EncargadoInformesViajesComponent', () => {
  let component: EncargadoInformesViajesComponent;
  let fixture: ComponentFixture<EncargadoInformesViajesComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ EncargadoInformesViajesComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(EncargadoInformesViajesComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
