import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { RemiseroMenuComponent } from './remisero-menu.component';

describe('RemiseroMenuComponent', () => {
  let component: RemiseroMenuComponent;
  let fixture: ComponentFixture<RemiseroMenuComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ RemiseroMenuComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(RemiseroMenuComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
