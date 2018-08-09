import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { ClienteMisViajesComponent } from './cliente-mis-viajes.component';

describe('ClienteMisViajesComponent', () => {
  let component: ClienteMisViajesComponent;
  let fixture: ComponentFixture<ClienteMisViajesComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ ClienteMisViajesComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(ClienteMisViajesComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
