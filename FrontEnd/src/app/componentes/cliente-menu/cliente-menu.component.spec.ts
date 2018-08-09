import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { ClienteMenuComponent } from './cliente-menu.component';

describe('ClienteMenuComponent', () => {
  let component: ClienteMenuComponent;
  let fixture: ComponentFixture<ClienteMenuComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ ClienteMenuComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(ClienteMenuComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
