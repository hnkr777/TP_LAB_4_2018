import { TestBed, inject } from '@angular/core/testing';

import { empleadosService } from './empleados.service';

describe('empleadosService', () => {
  beforeEach(() => {
    TestBed.configureTestingModule({
      providers: [empleadosService]
    });
  });

  it('should be created', inject([empleadosService], (service: empleadosService) => {
    expect(service).toBeTruthy();
  }));
});
