<?php

require_once 'conexion.php';
$tituloPagina = "Editar Vacuna";

$pdo = conectarDB();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $pdo->prepare(
        "UPDATE vacunas
            SET nombre_vacuna = :nombre,
                fecha_aplicacion = :fecha_aplicacion,
                proxima_dosis = :proxima_dosis
          WHERE id_vacuna = :id"
    );
    $stmt->execute([
        ':nombre'          => trim($_POST['nombre_vacuna']),
        ':fecha_aplicacion' => $_POST['fecha_aplicacion'],
        ':proxima_dosis'    => $_POST['proxima_dosis'] !== '' ? $_POST['proxima_dosis'] : null,
        ':id'              => (int) $_POST['id_vacuna'],
    ]);
    header('Location: historial_vacunacion.php?actualizado=1');
    exit;
}

if (!isset($_GET['id'])) {
    header('Location: historial_vacunacion.php');
    exit;
}

$stmt = $pdo->prepare(
    "SELECT v.*, p.nombre_mascota
     FROM vacunas v
     JOIN pacientes p ON p.id_paciente = v.id_paciente
     WHERE v.id_vacuna = :id"
);
$stmt->execute([':id' => (int) $_GET['id']]);
$vacuna = $stmt->fetch();

if (!$vacuna) {
    header('Location: historial_vacunacion.php');
    exit;
}

include 'includes/header.php';
?>

<div class="w3-container w3-card w3-white w3-round w3-padding-large">
    <h2 class="w3-border-bottom w3-padding-bottom" style="color:#2c6e63;">
        <i class="fa fa-flask"></i>&nbsp; Editar Vacuna — <?php echo htmlspecialchars($vacuna['nombre_mascota']); ?>
        <a href="historial_vacunacion.php" class="w3-button w3-round w3-right w3-small btn-vet">
            <i class="fa fa-arrow-left"></i>&nbsp; Volver al Historial
        </a>
    </h2>

    <form method="POST">
        <input type="hidden" name="id_vacuna" value="<?php echo $vacuna['id_vacuna']; ?>">

        <div class="w3-row-padding">
            <div class="w3-col m12">
                <label class="w3-text-grey"><b>Nombre de la Vacuna</b></label>
                <input class="w3-input w3-border w3-round" type="text" name="nombre_vacuna"
                       value="<?php echo htmlspecialchars($vacuna['nombre_vacuna']); ?>" required>
            </div>
        </div>

        <div class="w3-row-padding w3-margin-top">
            <div class="w3-col m6">
                <label class="w3-text-grey"><b>Fecha de Aplicación</b></label>
                <input class="w3-input w3-border w3-round" type="date" name="fecha_aplicacion"
                       value="<?php echo htmlspecialchars($vacuna['fecha_aplicacion']); ?>" required>
            </div>
            <div class="w3-col m6">
                <label class="w3-text-grey"><b>Próxima Dosis</b></label>
                <input class="w3-input w3-border w3-round" type="date" name="proxima_dosis"
                       value="<?php echo htmlspecialchars($vacuna['proxima_dosis'] ?? ''); ?>">
            </div>
        </div>

        <div class="w3-margin-top">
            <button type="submit" class="w3-button w3-round w3-padding btn-vet">
                <i class="fa fa-save"></i>&nbsp; Guardar Cambios
            </button>
            <a href="historial_vacunacion.php" class="w3-button w3-round w3-padding w3-light-grey w3-margin-left">Cancelar</a>
        </div>
    </form>
</div>

<?php include 'includes/footer.php'; ?>
