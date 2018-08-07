import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { AltaRemiseroComponent } from './alta-remisero.component';

describe('AltaRemiseroComponent', () => {
  let component: AltaRemiseroComponent;
  let fixture: ComponentFixture<AltaRemiseroComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ AltaRemiseroComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(AltaRemiseroComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
