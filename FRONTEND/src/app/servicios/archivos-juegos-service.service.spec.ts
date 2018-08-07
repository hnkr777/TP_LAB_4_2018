import { TestBed, inject } from '@angular/core/testing';

import { ArchivosJuegosServiceService } from './archivos-juegos-service.service';

describe('ArchivosJuegosServiceService', () => {
  beforeEach(() => {
    TestBed.configureTestingModule({
      providers: [ArchivosJuegosServiceService]
    });
  });

  it('should be created', inject([ArchivosJuegosServiceService], (service: ArchivosJuegosServiceService) => {
    expect(service).toBeTruthy();
  }));
});
