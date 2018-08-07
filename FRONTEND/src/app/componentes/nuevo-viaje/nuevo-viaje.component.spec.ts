import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { NuevoViajeComponent } from './nuevo-viaje.component';

describe('NuevoViajeComponent', () => {
  let component: NuevoViajeComponent;
  let fixture: ComponentFixture<NuevoViajeComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ NuevoViajeComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(NuevoViajeComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
