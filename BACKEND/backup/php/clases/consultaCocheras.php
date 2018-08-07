<?php
class cochera
{
    public $cantidad; // cantidad de veces que se uso entre fechas, o en total
    public $cochera; // numero de cochera
    public $discapacitados; // si es cochera para discapacitados

    public function __construct()
    {
        $this->discapacitados = ($this->discapacitados==1?true:false);
        if($this->cantidad > 0){}
        else
            $this->cantidad = 0;
    }
}


