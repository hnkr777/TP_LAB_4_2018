import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { VisorViajesComponent } from './visor-viajes.component';

describe('VisorViajesComponent', () => {
  let component: VisorViajesComponent;
  let fixture: ComponentFixture<VisorViajesComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ VisorViajesComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(VisorViajesComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
