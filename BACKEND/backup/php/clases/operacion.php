<?php
include_once "consultaCocheras.php";

class operacion
{
	public $id;
	public $juego;
	public $datos;
	
	/*public $ID;                 //auto
    public $ingreso_usuarioID;  //auto
    public $salida_usuarioID;   //null
    public $fecha_ingreso;      //auto
    public $fecha_salida;       //null
    public $importe;            // 0
    public $color;              //null
    public $patente;
    public $marca;              //null
	public $cochera;*/
	
	private static $db = "angular"; //"u438208802_datos";

    public function AgregarNueva()
	{
		$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
		$query = "SET time_zone = '-3:00';";
		$consulta = $objetoAccesoDato->RetornarConsulta($query);
		$consulta->execute();

		$query = "INSERT into ".self::$db.".partidas (id, juego, datos) values (:id, :juego, :datos);";

		//var_dump($query); die();

		$consulta = $objetoAccesoDato->RetornarConsulta($query);

		$consulta->bindValue(':id', $this->id, PDO::PARAM_INT);
		$consulta->bindValue(':juego', $this->juego, PDO::PARAM_STR);
		$consulta->bindValue(':datos', $this->datos, PDO::PARAM_STR);


		//$consulta->bindValue(':ingreso_usuarioID', $this->ingreso_usuarioID, PDO::PARAM_INT);
		// $consulta->bindValue(':marca', $this->marca, PDO::PARAM_STR);
		// $consulta->bindValue(':cochera', $this->cochera, PDO::PARAM_STR);
		// $consulta->bindValue(':discapacitados', $this->discapacitados, PDO::PARAM_INT);

		$consulta->execute();
		return $objetoAccesoDato->RetornarUltimoIdInsertado();
    }
	
	public static function TraerOperacionesPorUsuario()
	{
		try
		{
			$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
			$consulta = $objetoAccesoDato->RetornarConsulta(
				"SELECT usr.ID, usr.nombre, usr.apellido, usr.sexo, usr.turno, usr.perfil, usr.email, usr.foto, usr.fecha_creado, usr.habilitado, COUNT(opr.ingreso_usuarioID) AS cantidad
				FROM ".self::$db.".usuarios as usr
				INNER JOIN ".self::$db.".operaciones as opr ON usr.ID = opr.ingreso_usuarioID
				GROUP BY opr.ingreso_usuarioID;"
			);
			$consulta->execute();
			if($consulta->rowCount() == 0) return true;
			return $consulta->fetchAll(PDO::FETCH_CLASS, "usuario");
		}
		catch(Exception $e)
		{
			return false;
		}
		
	}

    public static function TraerTodasLasOperaciones()
	{
		/*$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
		$consulta = $objetoAccesoDato->RetornarConsulta("select * from ".self::$db.".operaciones");
		$consulta->execute();
		return $consulta->fetchAll(PDO::FETCH_CLASS, "operacion");*/

		$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
		$consulta = $objetoAccesoDato->RetornarConsulta(
			"SELECT u.nombre, p.datos, p.juego FROM ".self::$db.".partidas as p, ".self::$db.".usuarios as u where p.id = u.id order by p.juego"
		); // "select * from ".self::$db.".partidas"
		$consulta->execute();
		return $consulta->fetchAll(PDO::FETCH_CLASS, "operacion");
	}
	
	public static function TraerTodasLasCocherasOcupadas()
	{
		$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
		$consulta = $objetoAccesoDato->RetornarConsulta("select * from ".self::$db.".operaciones where fecha_salida is null");
		$consulta->execute();
		return $consulta->fetchAll(PDO::FETCH_CLASS, "operacion");
	}

	public static function getStatistics($desde = null, $hasta = null)
	{
		$cocheras = array();
		$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
		if($desde != null && ($hasta != null))
		{
			$consulta = $objetoAccesoDato->RetornarConsulta("SELECT cochera, COUNT(*) AS cantidad FROM ".self::$db.".operaciones WHERE fecha_ingreso between :desde and :hasta GROUP BY cochera ORDER BY cochera asc;");
			$consulta->bindValue(':desde', $desde, PDO::PARAM_STR);
			$consulta->bindValue(':hasta', $hasta, PDO::PARAM_STR);
		}
		else
		{
			$consulta = $objetoAccesoDato->RetornarConsulta("SELECT discapacitados, cochera, COUNT(*) AS cantidad FROM ".self::$db.".operaciones GROUP BY cochera ORDER BY cochera asc;");
		}

		$consulta->execute();
		$cocheras = $consulta->fetchAll(PDO::FETCH_CLASS, "cochera");

		$tmp = array();
		for ($i = 1; $i < 25; $i++) {
			$cc = new cochera();
			$cc->discapacitados = ($i > 0 && $i < 4);
			$cc->cochera = $i;
			array_push($tmp, $cc);
		}
		foreach ($cocheras as $key => $value) {
			$tmp[$value->cochera-1] = $value;
		}

		$consulta = $objetoAccesoDato->RetornarConsulta("SELECT COUNT(*) AS cantidadDiscapacitados FROM ".self::$db.".operaciones WHERE discapacitados = 1;");

		$consulta->execute();
		array_push ($tmp, $consulta->fetch(PDO::FETCH_ASSOC));

		return $tmp;
	}

	public static function ___getStatistics($fecha_desde = null, $fecha_hasta = null) // devuelve la cochera mas utilizada, la menos y las que no se usaron, entre las fechas dadas
	{
		$cons = new cochera();
		$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
		$consulta = $objetoAccesoDato->RetornarConsulta(
			"SELECT cochera, COUNT(*) AS cantidad FROM ".self::$db.".operaciones GROUP BY cochera ORDER BY cantidad DESC LIMIT 1;"
			//"SELECT cochera, COUNT(*) AS cantidad FROM datos.operaciones GROUP BY cochera ORDER BY cantidad DESC LIMIT 1;"
		);
		$consulta->execute();
		$tmp = $consulta->fetchAll(PDO::FETCH_CLASS, "operacion"); //var_dump($tmp[0]->cantidad); die();
		$cons->masUsada = $tmp[0]->cochera;
		$cons->masCantidad = $tmp[0]->cantidad;

		$consulta = $objetoAccesoDato->RetornarConsulta(
			"SELECT cochera, COUNT(*) AS cantidad FROM ".self::$db.".operaciones GROUP BY cochera ORDER BY cantidad ASC LIMIT 1;"
		);
		$consulta->execute();
		$tmp = $consulta->fetchAll(PDO::FETCH_CLASS, "operacion");
		$cons->menosUsada = $tmp[0]->cochera;
		$cons->menosCantidad = $tmp[0]->cantidad;

		$consulta = $objetoAccesoDato->RetornarConsulta(
			"select distinct cochera from ".self::$db.".operaciones order by cochera asc;"
		);
		$consulta->execute();
		$tmp = $consulta->fetchAll(PDO::FETCH_COLUMN);
		$arr = array();

		for ($i = 1; $i < 400 ; $i++)
		{
			if(!in_array($i, $tmp))
				array_push($arr, $i);
		}

		$cons->sinUsar = $arr;
		
		//var_dump($cons);
		return $cons;
	}

	public static function EstaLaCocheraOcupada($cochera)
	{
		$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
		$consulta = $objetoAccesoDato->RetornarConsulta("select * from ".self::$db.".operaciones where cochera = :cochera and fecha_salida is null");
		$consulta->bindValue(':cochera', $cochera, PDO::PARAM_INT);
		$consulta->execute();
		$count = $consulta->rowCount();
		return ($count > 0);
		/*if($count == 0)
		{
			return array("consulta" => "La cochera esta libre.");
		}
		return $consulta->fetchAll(PDO::FETCH_CLASS, "operacion");*/
    }

    public function borrarOperacion()
	{
	 	$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
		$consulta = $objetoAccesoDato->RetornarConsulta("delete from ".self::$db.".operaciones WHERE id =:id;");

		$consulta->bindValue(':id', $this->ID, PDO::PARAM_INT);
		$consulta->execute();
		return $consulta->rowCount();
    }
    
    public function terminarOperacion()
	{
		$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
		$query = "SET time_zone = '-3:00';";
		$consulta = $objetoAccesoDato->RetornarConsulta($query);
		$consulta->execute();
		
		$consulta = $objetoAccesoDato->RetornarConsulta(
			"UPDATE ".self::$db.".operaciones 
            SET salida_usuarioID = :salida_usuarioID, fecha_salida = CURRENT_TIMESTAMP(), importe = :importe 
            WHERE ID = :id;"
		);

		$consulta->bindValue(':id', $this->ID, PDO::PARAM_INT);
        $consulta->bindValue(':salida_usuarioID', $this->salida_usuarioID, PDO::PARAM_INT);
        $consulta->bindValue(':importe', $this->importe, PDO::PARAM_INT);
		$consulta->execute();

		if($consulta->rowCount() > 0)
		{
			$objResp = new stdClass();
			$consulta = $objetoAccesoDato->RetornarConsulta("select * from ".self::$db.".operaciones where ID = :id;");
			$consulta->bindValue(':id', $this->ID, PDO::PARAM_INT);
			$consulta->execute();
			$objResp->operacion = $consulta->fetchAll(PDO::FETCH_CLASS, "operacion")[0];
			$objResp->count = $consulta->rowCount();
			return $objResp;
		}
		else
		{
			return false;
		}
    }
    
    public static function traerOperacionAlta($patente)
	{
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objetoAccesoDato->RetornarConsulta("select * from ".self::$db.".operaciones WHERE patente = :patente AND fecha_salida is null;");
        $consulta->bindValue(':patente', $patente, PDO::PARAM_STR);
        $consulta->execute();
		$entidadBuscado = $consulta->fetchObject('operacion');
        
        return (($consulta->rowCount() > 0) ? $entidadBuscado : false);
	}

}

?>
