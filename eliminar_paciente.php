<?php

require_once 'conexion.php';

if (isset($_GET['id'])) {
    $id = (int) $_GET['id'];

    $pdo = conectarDB();
    $stmt = $pdo->prepare("DELETE FROM pacientes WHERE id_paciente = :id");
    $stmt->execute([':id' => $id]);
}

$pagina = isset($_GET['pagina']) ? (int) $_GET['pagina'] : 1;
header('Location: listar_pacientes.php?pagina=' . $pagina . '&eliminado=1');
exit;
