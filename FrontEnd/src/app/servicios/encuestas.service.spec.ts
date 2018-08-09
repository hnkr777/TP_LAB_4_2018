import { TestBed, inject } from '@angular/core/testing';

import { EncuestasService } from './encuestas.service';

describe('EncuestasService', () => {
  beforeEach(() => {
    TestBed.configureTestingModule({
      providers: [EncuestasService]
    });
  });

  it('should be created', inject([EncuestasService], (service: EncuestasService) => {
    expect(service).toBeTruthy();
  }));
});
