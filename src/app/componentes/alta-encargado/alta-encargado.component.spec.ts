import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { AltaEncargadoComponent } from './alta-encargado.component';

describe('AltaEncargadoComponent', () => {
  let component: AltaEncargadoComponent;
  let fixture: ComponentFixture<AltaEncargadoComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ AltaEncargadoComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(AltaEncargadoComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
