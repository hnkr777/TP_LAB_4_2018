<?php

include_once $_SERVER['DOCUMENT_ROOT']."/PHP/Entidades/vehiculo.php";
include_once $_SERVER['DOCUMENT_ROOT']."/PHP/SQL/AccesoDatos.php";
include_once $_SERVER['DOCUMENT_ROOT']."/PHP/EntidadesPDO/empleadoPDO.php";

abstract class vehiculoPDO{

	public static function traervehiculos()
	{
		$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();

		$consulta =$objetoAccesoDato->RetornarConsulta
		(
			"
				SELECT 
					veh.id, 
					emp.email as remisero,
                    niv.nombre as nivel_comodidad,
					asci.cantidad as ascientos_disponibles,
                    veh.suspendido as suspendido
				FROM 
                	vehiculos as veh, 
                    empleados as emp, 
                    nivel_comodidad as niv, 
                    ascientos_disponibles as asci
               	WHERE
                	id_remisero = emp.id AND
                    nivel_comodidad = niv.id AND
                    ascientos_disponibles = asci.id
			"
		);

		$consulta->execute();			
		return $consulta->fetchAll(PDO::FETCH_CLASS, "vehiculo");		
	}

	public static function traerVehiculoPorId($id)
	{
		$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
		$consulta = $objetoAccesoDato->RetornarConsulta
		("
			SELECT 
				veh.id , 
				emp.email as remisero,
                niv.nombre as nivel_comodidad,
				asci.cantidad as ascientos_disponibles,
                veh.suspendido as suspendido
			FROM 
            	vehiculos as veh, 
                empleados as emp, 
                nivel_comodidad as niv, 
                ascientos_disponibles as asci
           	WHERE
            	id_remisero = emp.id AND
                nivel_comodidad = niv.id AND
                ascientos_disponibles = asci.id AND
                veh.id = :id
		");
		$consulta->bindValue(':id', $id, PDO::PARAM_INT);	
		$consulta->execute();			
		return ($consulta->fetchAll(PDO::FETCH_CLASS, "vehiculo"))[0];	
	}

	public static function altaVehiculo($vehiculo)
	{
		$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
		$consulta = $objetoAccesoDato->RetornarConsulta
		("
			INSERT INTO 
				vehiculos(id_remisero, nivel_comodidad, ascientos_disponibles) 
			VALUES 
				(
			        (SELECT emp.id from empleados as emp where emp.email=:remisero),
			       	(SELECT niv.id from nivel_comodidad as niv where niv.nombre=:nivel_comodidad),
			        (SELECT asci.id from ascientos_disponibles as asci where asci.cantidad=:ascientos_disponibles)
			    )
		");

		$consulta->bindValue(':remisero',$vehiculo->remisero, PDO::PARAM_STR);
		$consulta->bindValue(':nivel_comodidad',$vehiculo->nivel_comodidad, PDO::PARAM_STR);
		$consulta->bindValue(':ascientos_disponibles',$vehiculo->ascientos_disponibles, PDO::PARAM_STR);

		try{

			$returnValue = $consulta->execute();

		} catch (Exception $e){

			$returnValue = 0;

		}

		return $returnValue;
	}

	public static function modificarVehiculo($vehiculo)
	{
		$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
		$consulta = $objetoAccesoDato->RetornarConsulta
		("
			UPDATE vehiculos
			SET 
				id_remisero = (SELECT emp.id from empleados as emp where emp.email=:remisero),
				nivel_comodidad = (SELECT niv.id from nivel_comodidad as niv where niv.nombre=:nivel_comodidad),
				ascientos_disponibles = (SELECT asci.id from ascientos_disponibles as asci where asci.cantidad=:ascientos_disponibles),
				suspendido = :suspendido
			WHERE
				id=:id
		");

		$consulta->bindValue(':id',$vehiculo->id, PDO::PARAM_INT);
		$consulta->bindValue(':remisero',$vehiculo->remisero, PDO::PARAM_STR);
		$consulta->bindValue(':nivel_comodidad',$vehiculo->nivel_comodidad, PDO::PARAM_STR);
		$consulta->bindValue(':ascientos_disponibles',$vehiculo->ascientos_disponibles, PDO::PARAM_STR);
		$consulta->bindValue(':suspendido',$vehiculo->suspendido, PDO::PARAM_INT);

		try{

			$returnValue = $consulta->execute();
			
		} catch (Exception $e){

			$returnValue = 0;

		}

		return $returnValue;
	}

}

?>