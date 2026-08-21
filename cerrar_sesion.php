<?php

session_start();
$_SESSION = [];
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000, $params["path"], $params["domain"], $params["secure"], $params["httponly"]);
}
session_destroy();
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>VetPets Rixy Cáceres | Sesión Cerrada</title>
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<style>body{font-family:"Segoe UI",Arial,sans-serif; background-color:#f1f6f5;}</style>
</head>
<body class="w3-center" style="padding-top:120px;">
    <div class="w3-card w3-white w3-round w3-padding-large w3-margin-auto" style="max-width:420px;">
        <i class="fa fa-paw" style="font-size:56px; color:#2c6e63;"></i>
        <h2 style="color:#2c6e63;">Sesión cerrada</h2>
        <p class="w3-text-grey">Has salido correctamente del panel administrativo de VetPets Rixy Cáceres.</p>
        <a href="dashboard_vet.php" class="w3-button w3-round w3-padding" style="background-color:#2c6e63; color:#fff;">
            <i class="fa fa-sign-in"></i>&nbsp; Volver a Iniciar Sesión
        </a>
    </div>
    <footer class="w3-margin-top w3-small w3-text-grey">
        &copy; <?php echo date('Y'); ?> VetPets Rixy Cáceres — Desarrollado por <b>Rixy Gisselle Cáceres Moncada</b>
    </footer>
</body>
</html>
