<?php 
	/**
	 * 
	 */
	class Db extends PDO{
		
		private $host;
		private $db;
		private $user;
		private $password;

		public function __construct()
		{
			$this->host = 'localhost';
			$this->db = 'mistareas';
			$this->user = 'root';
			$this->password = '';

			try {
				$conexion = 'mysql:host='.$this->host.';dbname='.$this->db.';';
				$opciones = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
							 PDO::ATTR_EMULATE_PREPARES => false];
				$pdo = parent::__construct($conexion,$this->user,$this->password,$opciones);
				return $pdo;

			} catch (PDOException $e) {
				print_r("Error en la conexión a la bd: " . $e->getMessage());
			}
		}
	}
 ?>