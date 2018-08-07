<?php

include_once $_SERVER['DOCUMENT_ROOT']."/PHP/Entidades/viaje.php";
include_once $_SERVER['DOCUMENT_ROOT']."/PHP/SQL/AccesoDatos.php";
include_once $_SERVER['DOCUMENT_ROOT']."/PHP/EntidadesPDO/empleadoPDO.php";

abstract class viajePDO{

	public static function traerviajes()
	{
		$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();

		$consulta =$objetoAccesoDato->RetornarConsulta
		(
			"
				SELECT 
				    via.id, 
				    (SELECT est.estado from estado_viaje as est where via.estado_viaje = est.id) as estado_viaje,
				    (SELECT emp1.email from empleados as emp1 where via.id_chofer = emp1.id) as id_chofer,
				    (SELECT emp2.email from empleados as emp2 where via.id_cliente = emp2.id) as id_cliente,
				    via.fecha_hora_viaje,
				    via.origen,
				    via.destino,
				    (SELECT med.nombre from medios_de_pago as med where via.medio_de_pago = med.id) as medio_de_pago,
				    (SELECT niv.nombre from nivel_comodidad as niv where via.comodidad_solicitada = niv.id) as comodidad_solicitada,
				    (SELECT asci.cantidad from ascientos_disponibles as asci where via.cantidad_de_ascientos_solicitados = asci.id) as cantidad_de_ascientos_solicitados,
                    via.costo
				from 
					viajes as via
			"
		);

		$consulta->execute();			
		return $consulta->fetchAll(PDO::FETCH_CLASS, "viaje");		
	}

	public static function traerviajePorId($id)
	{
		$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
		$consulta = $objetoAccesoDato->RetornarConsulta
		("
				SELECT 
				    via.id, 
				    (SELECT est.estado from estado_viaje as est where via.estado_viaje = est.id) as estado_viaje,
				    (SELECT emp1.email from empleados as emp1 where via.id_chofer = emp1.id) as id_chofer,
				    (SELECT emp2.email from empleados as emp2 where via.id_cliente = emp2.id) as id_cliente,
				    via.fecha_hora_viaje,
				    via.origen,
				    via.destino,
				    (SELECT med.nombre from medios_de_pago as med where via.medio_de_pago = med.id) as medio_de_pago,
				    (SELECT niv.nombre from nivel_comodidad as niv where via.comodidad_solicitada = niv.id) as comodidad_solicitada,
				    (SELECT asci.cantidad from ascientos_disponibles as asci where via.cantidad_de_ascientos_solicitados = asci.id) as cantidad_de_ascientos_solicitados,
                    via.costo
				FROM 
					viajes as via
				WHERE
					via.id = :id
		");
		$consulta->bindValue(':id', $id, PDO::PARAM_INT);	
		$consulta->execute();			
		return ($consulta->fetchAll(PDO::FETCH_CLASS, "viaje"))[0];	
	}

	public static function altaviaje($ArrayDeParametros)
	{
		$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
		$consulta = $objetoAccesoDato->RetornarConsulta
		("
			INSERT INTO 
				viajes(id_cliente, 	fecha_hora_viaje, origen, destino, medio_de_pago, comodidad_solicitada, cantidad_de_ascientos_solicitados, costo) 
			VALUES 
			(
			    (SELECT emp.id from empleados as emp where emp.email=:id_cliente),
			    :fecha_hora_viaje,
			    :origen,
			    :destino,
			    (SELECT med.id from medios_de_pago as med where med.nombre=:medio_de_pago),
			    (SELECT niv.id from nivel_comodidad as niv where niv.nombre=:comodidad_solicitada),
			    (SELECT asci.id from ascientos_disponibles as asci where asci.cantidad=:cantidad_de_ascientos_solicitados),
			    :costo
			)
		");

		$consulta->bindValue(':id_cliente',$ArrayDeParametros['id_cliente'], PDO::PARAM_STR);
		$consulta->bindValue(':fecha_hora_viaje',$ArrayDeParametros['fecha_hora_viaje'], PDO::PARAM_STR);
		$consulta->bindValue(':origen',$ArrayDeParametros['origen'], PDO::PARAM_STR);
		$consulta->bindValue(':destino',$ArrayDeParametros['destino'], PDO::PARAM_STR);
		$consulta->bindValue(':medio_de_pago',$ArrayDeParametros['medio_de_pago'], PDO::PARAM_STR);
		$consulta->bindValue(':comodidad_solicitada',$ArrayDeParametros['comodidad_solicitada'], PDO::PARAM_STR);
		$consulta->bindValue(':cantidad_de_ascientos_solicitados',$ArrayDeParametros['cantidad_de_ascientos_solicitados'], PDO::PARAM_STR);
		$consulta->bindValue(':costo',$ArrayDeParametros['costo'], PDO::PARAM_STR);

		try{

			$returnValue = $consulta->execute();

		} catch (Exception $e){

			$returnValue = 0;

		}

		return $returnValue;
	}

	public static function modificarviaje($viaje)
	{
		$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
		$consulta = $objetoAccesoDato->RetornarConsulta
		("
			UPDATE viajes
			SET
				estado_viaje = (SELECT est.id from estado_viaje as est where est.estado = :estado_viaje),
			    id_chofer = (SELECT emp1.id from empleados as emp1 where emp1.email = :id_chofer),
			    id_cliente = (SELECT emp2.id from empleados as emp2 where emp2.email = :id_cliente),
			    fecha_hora_viaje = :fecha_hora_viaje,
			    origen = :origen,
			    destino = :destino,
			    medio_de_pago = (SELECT med.id from medios_de_pago as med where med.nombre = :medio_de_pago),
			    comodidad_solicitada = (SELECT niv.id from nivel_comodidad as niv where niv.nombre = :comodidad_solicitada),
			    cantidad_de_ascientos_solicitados = (SELECT asci.id from ascientos_disponibles as asci where asci.cantidad = :cantidad_de_ascientos_solicitados),
			    costo = :costo
			WHERE
				id = :id
		");

		$consulta->bindValue(':id',$viaje->id, PDO::PARAM_INT);
		$consulta->bindValue(':estado_viaje',$viaje->estado_viaje, PDO::PARAM_INT);
		$consulta->bindValue(':id_chofer',$viaje->id_chofer, PDO::PARAM_INT);
		$consulta->bindValue(':id_cliente',$viaje->id_cliente, PDO::PARAM_INT);
		$consulta->bindValue(':fecha_hora_viaje',$viaje->fecha_hora_viaje, PDO::PARAM_STR);
		$consulta->bindValue(':origen',$viaje->origen, PDO::PARAM_STR);
		$consulta->bindValue(':destino',$viaje->destino, PDO::PARAM_STR);
		$consulta->bindValue(':medio_de_pago',$viaje->medio_de_pago, PDO::PARAM_INT);
		$consulta->bindValue(':comodidad_solicitada',$viaje->comodidad_solicitada, PDO::PARAM_INT);
		$consulta->bindValue(':cantidad_de_ascientos_solicitados',$viaje->cantidad_de_ascientos_solicitados, PDO::PARAM_INT);
		$consulta->bindValue(':costo',$viaje->costo, PDO::PARAM_STR);

		try{

			$returnValue = $consulta->execute();
			
		} catch (Exception $e){

			$returnValue = 0;

		}

		return $returnValue;
	}

}

?>