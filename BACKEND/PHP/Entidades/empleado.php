<?php
class empleado
{
    public $id;
    public $email;
    public $password;
    public $perfil;
    public $suspendido;

  	public function __toString()
    {
      return $this->id." ".$this->email." ".$this->password." ".$this->turno." ".$this->suspendido;
    }

    public function expose() {
      return get_object_vars($this);
    }

}


?>