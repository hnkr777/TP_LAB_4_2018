import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { RemiseroMisViajesComponent } from './remisero-mis-viajes.component';

describe('RemiseroMisViajesComponent', () => {
  let component: RemiseroMisViajesComponent;
  let fixture: ComponentFixture<RemiseroMisViajesComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ RemiseroMisViajesComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(RemiseroMisViajesComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
