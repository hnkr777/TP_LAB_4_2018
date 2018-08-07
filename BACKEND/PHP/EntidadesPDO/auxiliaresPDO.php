<?php

include_once $_SERVER['DOCUMENT_ROOT']."/PHP/SQL/AccesoDatos.php";
class auxiliaresPDO {

    public function TraerAscientos()
    {
		$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();

		$consulta =$objetoAccesoDato->RetornarConsulta
		(
			"
				SELECT 
				    *
				from 
					ascientos_disponibles
			"
		);

		$consulta->execute();			
		return $consulta->fetchAll(PDO::FETCH_KEY_PAIR);		
    }

    public function TraerCalificaciones()
    {
		$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();

		$consulta =$objetoAccesoDato->RetornarConsulta
		(
			"
				SELECT 
				    *
				from 
					calificaciones
			"
		);

		$consulta->execute();			
		return $consulta->fetchAll(PDO::FETCH_KEY_PAIR);		
    }

    public function TraerEstadosViaje()
    {
		$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();

		$consulta =$objetoAccesoDato->RetornarConsulta
		(
			"
				SELECT 
				    *
				from 
					estado_viaje
			"
		);

		$consulta->execute();			
		return $consulta->fetchAll(PDO::FETCH_KEY_PAIR);		
    }

    public function TraerMediosDePago()
    {
		$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();

		$consulta =$objetoAccesoDato->RetornarConsulta
		(
			"
				SELECT 
				    *
				from 
					medios_de_pago
			"
		);

		$consulta->execute();			
		return $consulta->fetchAll(PDO::FETCH_KEY_PAIR);		
    }

    public function TraerNivelesComodidad()
    {
		$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();

		$consulta =$objetoAccesoDato->RetornarConsulta
		(
			"
				SELECT 
				    *
				from 
					nivel_comodidad
			"
		);

		$consulta->execute();			
		return $consulta->fetchAll(PDO::FETCH_KEY_PAIR);		
    }

    public function TraerPerfiles()
    {
		$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();

		$consulta =$objetoAccesoDato->RetornarConsulta
		(
			"
				SELECT 
				    *
				from 
					perfiles
			"
		);

		$consulta->execute();			
		return $consulta->fetchAll(PDO::FETCH_KEY_PAIR);		
    }

}


?>