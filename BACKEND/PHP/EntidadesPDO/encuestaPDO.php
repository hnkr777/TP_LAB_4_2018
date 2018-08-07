<?php

include_once $_SERVER['DOCUMENT_ROOT']."/PHP/SQL/AccesoDatos.php";

abstract class encuestaPDO{

	public static function traerencuestas()
	{
		$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();

		$consulta =$objetoAccesoDato->RetornarConsulta
		(
			"
				SELECT 
					id,
				    (SELECT cal1.nombre from calificaciones as cal1 WHERE cal1.id = enc.comportamiento_conductor) AS comportamiento_conductor,
				    (SELECT cal2.nombre from calificaciones as cal2 WHERE cal2.id = enc.conversacion_conductor) AS conversacion_conductor,
				    (SELECT cal3.nombre from calificaciones as cal3 WHERE cal3.id = enc.puntualidad_conductor) AS puntualidad_conductor,
				    (SELECT cal4.nombre from calificaciones as cal4 WHERE cal4.id = enc.limpieza_vehiculo) AS limpieza_vehiculo,
				    (SELECT cal5.nombre from calificaciones as cal5 WHERE cal5.id = enc.estado_vehiculo) AS estado_vehiculo,
				    (SELECT cal6.nombre from calificaciones as cal6 WHERE cal6.id = enc.duracion_viaje) AS duracion_viaje,
				    (SELECT cal7.nombre from calificaciones as cal7 WHERE cal7.id = enc.calificacion_servicio) AS calificacion_servicio,
				    recomendaria_servicio,
				    foto01,
				    foto02,
				    foto03,
				    /*(SELECT emp.email FROM viajes as via, empleados as emp WHERE enc.viaje = via.id AND via.id_cliente = emp.id) AS cliente,
				    (SELECT emp.email FROM viajes as via, empleados as emp WHERE enc.viaje = via.id AND via.id_chofer = emp.id) AS conductor,*/
				    viaje,
				    comentario
				FROM
					encuestas as enc
			"
		);

		$consulta->execute();			
		return $consulta->fetchAll(PDO::FETCH_ASSOC);		
	}

	public static function traerencuestaPorId($id)
	{
		$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
		$consulta = $objetoAccesoDato->RetornarConsulta
		("
			SELECT 
				id,
			    (SELECT cal1.nombre from calificaciones as cal1 WHERE cal1.id = enc.comportamiento_conductor) AS comportamiento_conductor,
			    (SELECT cal2.nombre from calificaciones as cal2 WHERE cal2.id = enc.conversacion_conductor) AS conversacion_conductor,
			    (SELECT cal3.nombre from calificaciones as cal3 WHERE cal3.id = enc.puntualidad_conductor) AS puntualidad_conductor,
			    (SELECT cal4.nombre from calificaciones as cal4 WHERE cal4.id = enc.limpieza_vehiculo) AS limpieza_vehiculo,
			    (SELECT cal5.nombre from calificaciones as cal5 WHERE cal5.id = enc.estado_vehiculo) AS estado_vehiculo,
			    (SELECT cal6.nombre from calificaciones as cal6 WHERE cal6.id = enc.duracion_viaje) AS duracion_viaje,
			    (SELECT cal7.nombre from calificaciones as cal7 WHERE cal7.id = enc.calificacion_servicio) AS calificacion_servicio,
			    recomendaria_servicio,
			    foto01,
			    foto02,
			    foto03,
			    /*(SELECT emp.email FROM viajes as via, empleados as emp WHERE enc.viaje = via.id AND via.id_cliente = emp.id) AS cliente,
			    (SELECT emp.email FROM viajes as via, empleados as emp WHERE enc.viaje = via.id AND via.id_chofer = emp.id) AS conductor,*/
			    comentario
			FROM
				encuestas as enc
			WHERE
				enc.id = :id
		");
		$consulta->bindValue(':id', $id, PDO::PARAM_INT);	
		$consulta->execute();			
		return $consulta->fetchAll(PDO::FETCH_ASSOC);	
	}

	public static function altaencuesta($ArrayDeParametros)
	{
		$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
		$consulta = $objetoAccesoDato->RetornarConsulta
		("
			INSERT INTO 
				encuestas(comportamiento_conductor, conversacion_conductor, puntualidad_conductor, limpieza_vehiculo, estado_vehiculo, duracion_viaje, calificacion_servicio, recomendaria_servicio, comentario, foto01, foto02, foto03, viaje) 
			VALUES
			(
				(SELECT cal1.id from calificaciones as cal1 WHERE cal1.nombre = :comportamiento_conductor),
			    (SELECT cal2.id from calificaciones as cal2 WHERE cal2.nombre = :conversacion_conductor),
			    (SELECT cal3.id from calificaciones as cal3 WHERE cal3.nombre = :puntualidad_conductor),
			    (SELECT cal4.id from calificaciones as cal4 WHERE cal4.nombre = :limpieza_vehiculo),
			    (SELECT cal5.id from calificaciones as cal5 WHERE cal5.nombre = :estado_vehiculo),
			    (SELECT cal6.id from calificaciones as cal6 WHERE cal6.nombre = :duracion_viaje),
			    (SELECT cal7.id from calificaciones as cal7 WHERE cal7.nombre = :calificacion_servicio),
			    :recomendaria_servicio,
			    :comentario,
			    :foto01,
			    :foto02,
			    :foto03,
			    :viaje
			)
		");

		$consulta->bindValue(':comportamiento_conductor',$ArrayDeParametros['comportamiento_conductor'], PDO::PARAM_STR);
		$consulta->bindValue(':conversacion_conductor',$ArrayDeParametros['conversacion_conductor'], PDO::PARAM_STR);
		$consulta->bindValue(':puntualidad_conductor',$ArrayDeParametros['puntualidad_conductor'], PDO::PARAM_STR);
		$consulta->bindValue(':limpieza_vehiculo',$ArrayDeParametros['limpieza_vehiculo'], PDO::PARAM_STR);
		$consulta->bindValue(':estado_vehiculo',$ArrayDeParametros['estado_vehiculo'], PDO::PARAM_STR);
		$consulta->bindValue(':duracion_viaje',$ArrayDeParametros['duracion_viaje'], PDO::PARAM_STR);
		$consulta->bindValue(':calificacion_servicio',$ArrayDeParametros['calificacion_servicio'], PDO::PARAM_STR);
		$consulta->bindValue(':recomendaria_servicio',$ArrayDeParametros['recomendaria_servicio'], PDO::PARAM_INT);
		$consulta->bindValue(':comentario',$ArrayDeParametros['comentario'], PDO::PARAM_STR);
		$consulta->bindValue(':foto01',$ArrayDeParametros['foto01'], PDO::PARAM_STR);
		$consulta->bindValue(':foto02',$ArrayDeParametros['foto02'], PDO::PARAM_STR);
		$consulta->bindValue(':foto03',$ArrayDeParametros['foto03'], PDO::PARAM_STR);
		$consulta->bindValue(':viaje',$ArrayDeParametros['viaje'], PDO::PARAM_INT);

		try{

			$returnValue = $consulta->execute();

		} catch (Exception $e){

			$returnValue = 0;

		}

		return $returnValue;
	}

}

?>