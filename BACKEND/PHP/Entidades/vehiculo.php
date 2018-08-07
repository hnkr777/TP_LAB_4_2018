<?php
class vehiculo
{
    public $id;
    public $remisero;
    public $nivel_comodidad;    
    public $ascientos_disponibles;
    public $suspendido;

  	public function __toString()
    {
      return $this->id." ".$this->remisero." ".$this->nivel_comodidad." ".$this->ascientos_disponibles." ".$this->suspendido;
    }

    public function expose() {
      return get_object_vars($this);
    }

}


?>