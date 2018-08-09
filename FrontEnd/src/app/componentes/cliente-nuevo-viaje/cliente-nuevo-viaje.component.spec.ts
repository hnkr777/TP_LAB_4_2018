import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { ClienteNuevoViajeComponent } from './cliente-nuevo-viaje.component';

describe('ClienteNuevoViajeComponent', () => {
  let component: ClienteNuevoViajeComponent;
  let fixture: ComponentFixture<ClienteNuevoViajeComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ ClienteNuevoViajeComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(ClienteNuevoViajeComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
