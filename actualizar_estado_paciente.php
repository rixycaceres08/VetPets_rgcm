<?php

require_once 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_paciente'], $_POST['estado'])) {
    $estadosValidos = ['Activo', 'Inactivo', 'Cancelado'];
    $estado = in_array($_POST['estado'], $estadosValidos, true) ? $_POST['estado'] : 'Activo';

    $pdo = conectarDB();
    $stmt = $pdo->prepare("UPDATE pacientes SET estado = :estado WHERE id_paciente = :id");
    $stmt->execute([
        ':estado' => $estado,
        ':id'     => (int) $_POST['id_paciente'],
    ]);
}

$pagina = isset($_POST['pagina']) ? (int) $_POST['pagina'] : 1;
$buscar = isset($_POST['buscar']) ? $_POST['buscar'] : '';
header('Location: listar_pacientes.php?pagina=' . $pagina . '&buscar=' . urlencode($buscar) . '&estado_actualizado=1');
exit;