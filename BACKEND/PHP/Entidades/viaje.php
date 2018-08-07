<?php
class viaje
{
    public $id;
    public $estado_viaje;
    public $id_chofer;
    public $id_cliente;
    public $fecha_hora_viaje;
    public $origen;
    public $destino;
    public $medio_de_pago;
    public $comodidad_solicitada;
    public $cantidad_de_ascientos_solicitados;
    public $costo;

  	public function __toString()
    {
      return $this->id." ".$this->estado_viaje." ".$this->id_chofer." ".$this->id_cliente." ".$this->fecha_hora_viaje." ".$this->origen." ".$this->destino." ".$this->medio_de_pago." ".$this->comodidad_solicitada." ".$this->cantidad_de_ascientos_solicitados." ".$this->costo;
    }

    public function expose() {
      return get_object_vars($this);
    }

}


?>