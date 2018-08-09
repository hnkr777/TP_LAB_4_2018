import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { EncargadoMenuComponent } from './encargado-menu.component';

describe('EncargadoMenuComponent', () => {
  let component: EncargadoMenuComponent;
  let fixture: ComponentFixture<EncargadoMenuComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ EncargadoMenuComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(EncargadoMenuComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
