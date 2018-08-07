<?php

include_once $_SERVER['DOCUMENT_ROOT']."/PHP/Entidades/empleado.php";
include_once $_SERVER['DOCUMENT_ROOT']."/PHP/SQL/AccesoDatos.php";

abstract class empleadoPDO{

	public static function traerEmpleados()
	{
		$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
		$consulta =$objetoAccesoDato->RetornarConsulta
		(
			 "
				SELECT emp.id , emp.email, emp.password, perfiles.nombre as perfil, emp.suspendido
				FROM empleados AS emp
				INNER JOIN perfiles ON perfiles.id = emp.perfil
			 "
		);
		$consulta->execute();			
		return $consulta->fetchAll(PDO::FETCH_CLASS, "empleado");		
	}

	public static function traerEmpleadoPorId($id)
	{
		$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
		$consulta = $objetoAccesoDato->RetornarConsulta
		("
			SELECT * FROM empleados WHERE id=:id
		");
		$consulta->bindValue(':id', $id, PDO::PARAM_INT);	
		$consulta->execute();			
		return $consulta->fetchAll(PDO::FETCH_CLASS, "empleado");		
	}

	public static function altaEmpleado($empleado)
	{
		$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
		$returnValue = 1;

		switch(mb_strtolower($empleado->perfil, 'UTF-8')){
			case "cliente":
				$empleado->perfil = "1";
				break;
			case "encargado":			
				$empleado->perfil = "2";
				break;
			case "remisero":			
				$empleado->perfil = "3";
				break;
		}

		$consulta = $objetoAccesoDato->RetornarConsulta
		(
			"
				INSERT INTO empleados(email, password, perfil)
				VALUES(:email,:password,:perfil)
			"
		);

		$consulta->bindValue(':email',$empleado->email, PDO::PARAM_STR);
		$consulta->bindValue(':password', $empleado->password, PDO::PARAM_STR);
		$consulta->bindValue(':perfil', $empleado->perfil, PDO::PARAM_STR);	

		try{

			$returnValue = $consulta->execute();

		} catch (Exception $e){

			$returnValue = 0;

		}

		return $returnValue;
	}

	public static function modificarEmpleado($empleado)
	{
		$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
		$returnValue = 1;

		switch(mb_strtolower($empleado->perfil, 'UTF-8')){
			case "cliente":
				$empleado->perfil = "1";
				break;
			case "encargado":			
				$empleado->perfil = "2";
				break;
			case "remisero":			
				$empleado->perfil = "3";
				break;
		}

		$consulta = $objetoAccesoDato->RetornarConsulta
		(
			"
				UPDATE empleados
				SET 
					email=:email, 
					password=:password, 
					perfil=:perfil,
					suspendido=:suspendido
				WHERE id=:id
			"
		);

		$consulta->bindValue(':email',$empleado->email, PDO::PARAM_STR);
		$consulta->bindValue(':password', $empleado->password, PDO::PARAM_STR);
		$consulta->bindValue(':perfil', $empleado->perfil, PDO::PARAM_STR);
		$consulta->bindValue(':id', $empleado->id, PDO::PARAM_INT);
		$consulta->bindValue(':suspendido', $empleado->suspendido, PDO::PARAM_INT);	

		try{

			$consulta->execute();
			$returnValue = $consulta->rowCount();

		} catch (Exception $e){

			$returnValue = 0;

		}

		return $returnValue;
	}

	public static function traerEmpleadoPorEmailYPassword($email,$password)
	{
		$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
		$consulta = $objetoAccesoDato->RetornarConsulta
		("
			SELECT id FROM empleados WHERE email=:email AND password=:password
		");
		$consulta->bindValue(':email', $email, PDO::PARAM_STR);
		$consulta->bindValue(':password', $password, PDO::PARAM_STR);	
		$consulta->execute();			
		$queryResponse = $consulta->fetch(PDO::FETCH_ASSOC);
		return $queryResponse["id"];		
	}

	public static function traerIdEmpleadoPorEmail($email)
	{
		$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
		$consulta = $objetoAccesoDato->RetornarConsulta
		("
			SELECT id FROM empleados WHERE email=:email
		");
		$consulta->bindValue(':email', $email, PDO::PARAM_STR);
		$consulta->execute();			
		$queryResponse = $consulta->fetch(PDO::FETCH_ASSOC);
		return $queryResponse["id"];		
	}
	
	public static function traerEstadoSuspendidoEmpleadoPorEmail($email)
	{
		$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
		$consulta = $objetoAccesoDato->RetornarConsulta
		("
			SELECT suspendido FROM empleados WHERE email=:email
		");
		$consulta->bindValue(':email', $email, PDO::PARAM_STR);
		$consulta->execute();			
		$queryResponse = $consulta->fetch(PDO::FETCH_ASSOC);
		return $queryResponse["suspendido"];
	}

	/* ============================================================================================================== */

	public static function empleadoValidation($email, $password)
	{
		$returnValue = 0;

		$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
		$consulta =$objetoAccesoDato->RetornarConsulta
		(
			 "
				SELECT id FROM empleados WHERE email=:email AND password=:password
			 "
		);
		$consulta->bindValue(':email', $email, PDO::PARAM_STR);
		$consulta->bindValue(':password', $password, PDO::PARAM_STR);	
		$consulta->execute();

		return $consulta->rowCount();
	}

	public static function RemiserosDisponibles()
	{
		$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
		$consulta =$objetoAccesoDato->RetornarConsulta
		(
			 "
				SELECT *
				FROM empleados 
				WHERE perfil = 3 AND id NOT IN (
					SELECT id_chofer 
					FROM viajes 
					WHERE estado_viaje = 1 AND id_chofer IS NOT NULL
				)
			 "
		);
		$consulta->execute();			
		return $consulta->fetchAll(PDO::FETCH_CLASS, "empleado");	
	}

}

?>