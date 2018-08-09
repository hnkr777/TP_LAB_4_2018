import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { RemiseroComponent } from './remisero.component';

describe('RemiseroComponent', () => {
  let component: RemiseroComponent;
  let fixture: ComponentFixture<RemiseroComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ RemiseroComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(RemiseroComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
