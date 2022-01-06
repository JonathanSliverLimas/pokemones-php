<?php
	error_reporting(0);
	if($_POST['enviado']){
		//echo "Presionaste el botón";
		$nombre = $_POST['nombre'];
		$edad = $_POST['edad'];

		if($nombre && $edad){
			echo "Bienvenido: $nombre, tienes $edad";
		}else{
			echo "Todos los campos son obligatorios";
		}
	}
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>Sitio web PHP</title>
	</head>
	<body>
		<form action="index.php" method="POST">
			<label>Nombre</label>
			<input type="text" name="nombre" id="nombre" value="<?php echo $nombre; ?>">
			<label>Edad</label>
			<input type="number" name="edad" id="edad" value="<?php echo $edad; ?>">
			<input type="hidden" name="enviado" id="enviado" value="1">
			<input type="submit" value="Enviar">
		</form>
	</body>
</html>