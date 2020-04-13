<?php
session_start();
session_destroy();
// Mandarlos a la pagina de login
header('Location: ../index.html');
?>
