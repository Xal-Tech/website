<?php
session_start();

// Agarra los datos de conexion
require '../connection-data.php';

// Se conecta con la informacion de arriba
$con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
if ( mysqli_connect_errno() ) {
    // Por si pasa un error al conectarse
	exit('Hubo un error al conectarse a MySQL: ' . mysqli_connect_error());
}

// Checamos si de verdad mandaron la informacion de la cuenta, isset() verifica que si mandaron algo
if ( !isset($_POST['username'], $_POST['password'], $_POST['cellnumber']) ) {
    // No mandaron los datos requeridos
	exit('Por favor llene los campos requeridos!');
}


// Preparamos el SQL
if ($stmt = $con->prepare('SELECT id FROM accounts WHERE username = ?')) {
	// Bind parameters (s = string, i = int, etc), el username es string entonces usamos "s"
	$stmt->bind_param('s', $_POST['username']);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) { // Checamos si existe el username
        echo 'El nombre de usuario ya existe';

    } else { // Nombre de usuario es nuevo
        // Preparamos el SQL
        if ($stmt = $con->prepare("INSERT INTO `accounts` (`username`, `password`, `cellnumber`) VALUES (?, ?, ?)")) {
            // Bind parameters (s = string, i = int, etc), son strings entonces usamos "s"
            $stmt->bind_param('sss', $_POST['username'], password_hash($_POST['password'], PASSWORD_DEFAULT), $_POST['cellnumber']);
            $stmt->execute();

            if ($stmt = $con->prepare('SELECT id FROM accounts WHERE username = ?')) {
                // Bind parameters (s = string, i = int, etc), son strings entonces usamos "s"
                $stmt->bind_param('s', $_POST['username']);
                $stmt->execute();
                $stmt->store_result();
                $stmt->bind_result($id);
                $stmt->fetch();

                $stmt->close();

                // Se crea la sesión, son como cookies pero el servidor las recuerda
                session_regenerate_id();
                $_SESSION['loggedin'] = TRUE;
                $_SESSION['name'] = $_POST['username'];
                $_SESSION['id'] = $id;
                header('Location: ../home.php');


            }

        }

    }

    $stmt->close();

}


?>