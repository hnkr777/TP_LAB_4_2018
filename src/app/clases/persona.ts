
export abstract class Persona {
    nombre: string;
    apellido: string;
    dni: number;
    email: string;
    clave: string;
    sexo: string;

    constructor(nombre?: string, apellido?: string, dni?: number, email?: string, clave?: string, sexo?: string) {
        if (nombre !== undefined) { this.nombre = nombre; }
        if (apellido !== undefined) { this.apellido = apellido; }
        if (dni !== undefined) { this.dni = dni; }
        if (email !== undefined) { this.email = email; }
        if (clave !== undefined) { this.clave = clave; }
        if (sexo !== undefined) { this.sexo = sexo; }
    }

    toJSON() {
        return JSON.stringify(this);
    }
}
