<?php 
	include 'db.php';

	$conexion = new Db();

	if ($_SERVER['REQUEST_METHOD'] == 'GET') {
		if (isset($_GET['id'])) {
			// Mostrar una tarea
			$query = $conexion->prepare("SELECT * FROM tareas WHERE id = :id");
			$query->bindValue(':id', $_GET['id']);
			$query->execute();
			header("HTTP/1.1 200 OK");
			header("Access-Control-Allow-Origin:*");
			header("Content-type: application/json");
			echo json_encode($query->fetch(PDO::FETCH_ASSOC));
			exit();
		} else {
			// Lista de tareas
			$query = $conexion->prepare("SELECT * FROM tareas");
			$query->execute();
			$query->setFetchMode(PDO::FETCH_ASSOC);
			header("HTTP/1.1 200 OK");
			header("Access-Control-Allow-Origin:*");
			header("Content-type: application/json");
			echo json_encode($query->fetchAll());
			exit();
		}
	}

	// Crear
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		$input = $_POST;
		$sql = "INSERT INTO tareas(tarea, completado, fecha)
				VALUES (:tarea, false, NOW())";
		$query = $conexion->prepare($sql);
		bindAllValues($query, $input);
		$query->execute();
		$postId = $conexion->lastInsertId();
		if ($postId) {
			$input['id'] = $postId;
			header("HTTP/1.1 200 OK");
			header("Access-Control-Allow-Origin:*");
			header("Content-type: application/json");
          	echo json_encode($input);
          	exit();
		}
	}

	// Borrar
	if ($_SERVER['REQUEST_METHOD'] == "DELETE") {
		$id = $_GET['id'];
		$query = $conexion->prepare("DELETE FROM tareas WHERE id = :id");
		$query->bindValue(':id', $id);
		$query->execute();
		header("HTTP/1.1 200 OK");
		header("Access-Control-Allow-Origin: *");
		exit();
	}

	// Editar
	if ($_SERVER['REQUEST_METHOD'] == "PUT") {
		
		$input = $_GET;
        $postId = $input['id'];
        $fields = getParams($input);
		$query = $conexion->prepare("UPDATE tareas 
									SET $fields 
									WHERE id = '$postId'");
		bindAllValues($query, $input);
		$query->execute();
		header("Access-Control-Allow-Origin: *");
		header("HTTP/1.1 200 OK");
		exit();
	}

	header("HTTP/1.1 400 Bad Request");

	// Asociar todos los parametros a un sql
    function bindAllValues($statement, $params) {
        foreach ($params as $param => $value) {
            $statement->bindValue(':'.$param, $value);
        }
        return $statement;
    }

    // obtener parametros para updates
    function getParams($input) {
        $filterParams = [];
        
        foreach($input as $param => $value) {
            $filterParams[] = "$param=:$param";
        }
        return implode(", ", $filterParams);
    }

 ?>