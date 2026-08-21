<?php

require_once 'conexion.php';
$tituloPagina = "Editar Cirugía";

$pdo = conectarDB();
$mensaje = "";

// ------------------------------------------------------------
// Guardar cambios
// ------------------------------------------------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $pdo->prepare(
        "UPDATE cirugias
            SET tipo_cirugia = :tipo,
                fecha_programada = :fecha,
                estado = :estado
          WHERE id_cirugia = :id"
    );
    $stmt->execute([
        ':tipo'   => trim($_POST['tipo_cirugia']),
        ':fecha'  => $_POST['fecha_programada'],
        ':estado' => $_POST['estado'],
        ':id'     => (int) $_POST['id_cirugia'],
    ]);
    header('Location: calendario_cirugias.php?actualizado=1');
    exit;
}

// ------------------------------------------------------------
// Cargar la cirugía a editar
// ------------------------------------------------------------
if (!isset($_GET['id'])) {
    header('Location: calendario_cirugias.php');
    exit;
}

$stmt = $pdo->prepare(
    "SELECT c.*, p.nombre_mascota
     FROM cirugias c
     JOIN pacientes p ON p.id_paciente = c.id_paciente
     WHERE c.id_cirugia = :id"
);
$stmt->execute([':id' => (int) $_GET['id']]);
$cirugia = $stmt->fetch();

if (!$cirugia) {
    header('Location: calendario_cirugias.php');
    exit;
}

// Formato para el input datetime-local (YYYY-MM-DDTHH:MM)
$fechaInput = $cirugia['fecha_programada'] ? date('Y-m-d\TH:i', strtotime($cirugia['fecha_programada'])) : '';

include 'includes/header.php';
?>

<div class="w3-container w3-card w3-white w3-round w3-padding-large">
    <h2 class="w3-border-bottom w3-padding-bottom" style="color:#2c6e63;">
        <i class="fa fa-calendar"></i>&nbsp; Editar Cirugía — <?php echo htmlspecialchars($cirugia['nombre_mascota']); ?>
        <a href="calendario_cirugias.php" class="w3-button w3-round w3-right w3-small btn-vet">
            <i class="fa fa-arrow-left"></i>&nbsp; Volver al Calendario
        </a>
    </h2>

    <form method="POST">
        <input type="hidden" name="id_cirugia" value="<?php echo $cirugia['id_cirugia']; ?>">

        <div class="w3-row-padding">
            <div class="w3-col m6">
                <label class="w3-text-grey"><b>Tipo de Cirugía</b></label>
                <input class="w3-input w3-border w3-round" type="text" name="tipo_cirugia"
                       value="<?php echo htmlspecialchars($cirugia['tipo_cirugia']); ?>" required>
            </div>
            <div class="w3-col m6">
                <label class="w3-text-grey"><b>Fecha y Hora Programada</b></label>
                <input class="w3-input w3-border w3-round" type="datetime-local" name="fecha_programada"
                       value="<?php echo $fechaInput; ?>" required>
            </div>
        </div>

        <div class="w3-row-padding w3-margin-top">
            <div class="w3-col m6">
                <label class="w3-text-grey"><b>Estado</b></label>
                <select class="w3-select w3-border w3-round" name="estado">
                    <option value="Programada" <?php echo $cirugia['estado'] === 'Programada' ? 'selected' : ''; ?>>Programada</option>
                    <option value="Realizada" <?php echo $cirugia['estado'] === 'Realizada' ? 'selected' : ''; ?>>Realizada</option>
                    <option value="Cancelada" <?php echo $cirugia['estado'] === 'Cancelada' ? 'selected' : ''; ?>>Cancelada</option>
                </select>
            </div>
        </div>

        <div class="w3-margin-top">
            <button type="submit" class="w3-button w3-round w3-padding btn-vet">
                <i class="fa fa-save"></i>&nbsp; Guardar Cambios
            </button>
            <a href="calendario_cirugias.php" class="w3-button w3-round w3-padding w3-light-grey w3-margin-left">Cancelar</a>
        </div>
    </form>
</div>

<?php include 'includes/footer.php'; ?>
