<?php

require_once 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nombre_mascota = trim($_POST['nombre_mascota'] ?? '');
    $especie        = trim($_POST['especie'] ?? '');
    $raza           = trim($_POST['raza'] ?? '');
    $edad           = trim($_POST['edad'] ?? '');
    $peso           = trim($_POST['peso'] ?? '');
    $color_pelaje   = trim($_POST['color_pelaje'] ?? '');
    $fecha_ingreso  = $_POST['fecha_ingreso'] !== '' ? $_POST['fecha_ingreso'] : null;

    $nombre_propietario = trim($_POST['nombre_propietario'] ?? '');
    $telefono            = trim($_POST['telefono'] ?? '');
    $correo              = trim($_POST['correo'] ?? '');
    $direccion           = trim($_POST['direccion'] ?? '');
    $dni_propietario     = trim($_POST['dni_propietario'] ?? '');

    $alergias                = trim($_POST['alergias'] ?? '');
    $observaciones_generales = trim($_POST['observaciones_generales'] ?? '');

    if ($nombre_mascota === '' || $especie === '' || $dni_propietario === '' || $nombre_propietario === '' || $telefono === '') {
        die('Faltan campos obligatorios: nombre de la mascota, especie, nombre del propietario, teléfono o DNI.');
    }

    try {
        $pdo = conectarDB();
        $pdo->beginTransaction();
        $stmtProp = $pdo->prepare(
            "INSERT INTO propietarios (dni, nombre_completo, telefono, correo, direccion)
             VALUES (:dni, :nombre, :telefono, :correo, :direccion)
             ON DUPLICATE KEY UPDATE
                nombre_completo = :nombre2,
                telefono = :telefono2,
                correo = :correo2,
                direccion = :direccion2"
        );
        $stmtProp->execute([
            ':dni'        => $dni_propietario,
            ':nombre'     => $nombre_propietario,
            ':telefono'   => $telefono,
            ':correo'     => $correo,
            ':direccion'  => $direccion,
            ':nombre2'    => $nombre_propietario,
            ':telefono2'  => $telefono,
            ':correo2'    => $correo,
            ':direccion2' => $direccion,
        ]);

        $stmtPac = $pdo->prepare(
            "INSERT INTO pacientes
                (nombre_mascota, especie, raza, edad, peso, color_pelaje, fecha_ingreso, estado,
                 alergias, observaciones_generales, dni_propietario)
             VALUES
                (:nombre_mascota, :especie, :raza, :edad, :peso, :color_pelaje, :fecha_ingreso, 'Activo',
                 :alergias, :observaciones_generales, :dni_propietario)"
        );
        $stmtPac->execute([
            ':nombre_mascota'          => $nombre_mascota,
            ':especie'                 => $especie,
            ':raza'                    => $raza,
            ':edad'                    => $edad,
            ':peso'                    => $peso !== '' ? $peso : null,
            ':color_pelaje'            => $color_pelaje,
            ':fecha_ingreso'           => $fecha_ingreso,
            ':alergias'                => $alergias,
            ':observaciones_generales' => $observaciones_generales,
            ':dni_propietario'         => $dni_propietario,
        ]);

        $pdo->commit();

        header('Location: registro_pacientes.php?registrado=1');
        exit;

    } catch (PDOException $e) {
        if (isset($pdo)) {
            $pdo->rollBack();
        }
        die("Error al guardar el paciente: " . $e->getMessage());
    }

} else {
    header('Location: registro_pacientes.php');
    exit;
}