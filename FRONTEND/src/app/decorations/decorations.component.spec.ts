import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { DecorationsComponent } from './decorations.component';

describe('DecorationsComponent', () => {
  let component: DecorationsComponent;
  let fixture: ComponentFixture<DecorationsComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ DecorationsComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(DecorationsComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
