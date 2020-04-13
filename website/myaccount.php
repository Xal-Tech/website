<?php
session_start();

// Si no esta logged in mandarlo a la pagina de login
if (!isset($_SESSION['loggedin'])) {
	header('Location: index.html');
	exit;
}

// Agarra los datos de conexion
require 'connection-data.php';

$con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
if (mysqli_connect_errno()) {
	exit('Failed to connect to MySQL: ' . mysqli_connect_error());
}
// Vamos a obtener el telefono y la contraseña de la base de datos
$stmt = $con->prepare('SELECT password, cellnumber FROM accounts WHERE id = ?');

// Usamos la id de la cuenta para buscar la info
$stmt->bind_param('i', $_SESSION['id']);
$stmt->execute();
$stmt->bind_result($password, $cellnumber); // Guardamos la info en estas variables
$stmt->fetch();
$stmt->close();
?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Profile Page</title>
	</head>
	<body>
        <?php include 'header.php'; ?>
        
		<div class="content">
			<h2>Profile Page</h2>
			<div>
				<p>Your account details are below:</p>
				<table>
					<tr>
						<td>Nombre de usuario:</td>
						<td><?=$_SESSION['name']?></td>
					</tr>
					<tr>
						<td>Contrase&ntilde;a:</td>
						<td><?=$password?></td>
					</tr>
					<tr>
						<td>Tel&eacute;fono:</td>
						<td><?=$cellnumber?></td>
					</tr>
				</table>
			</div>
		</div>
	</body>
</html>

