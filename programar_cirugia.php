<?php

require_once 'conexion.php';
$tituloPagina = "Programar Cirugía";

$pdo = conectarDB();
$mensaje = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $pdo->prepare(
        "INSERT INTO cirugias (id_paciente, tipo_cirugia, fecha_programada, estado)
         VALUES (:id_paciente, :tipo_cirugia, :fecha_programada, 'Programada')"
    );
    $stmt->execute([
        ':id_paciente'      => (int) $_POST['id_paciente'],
        ':tipo_cirugia'     => trim($_POST['tipo_cirugia']),
        ':fecha_programada' => $_POST['fecha_programada'],
    ]);
    $mensaje = "Cirugía programada correctamente.";
}

$pacientes = $pdo->query("SELECT id_paciente, nombre_mascota, especie FROM pacientes ORDER BY nombre_mascota ASC")->fetchAll();

include 'includes/header.php';
?>

<div class="w3-container w3-card w3-white w3-round w3-padding-large">
    <h2 class="w3-border-bottom w3-padding-bottom" style="color:#2c6e63;">
        <i class="fa fa-medkit"></i>&nbsp; Programar Cirugía
        <a href="calendario_cirugias.php" class="w3-button w3-round w3-right w3-small btn-vet">
            <i class="fa fa-calendar"></i>&nbsp; Ver Calendario
        </a>
    </h2>

    <?php if ($mensaje): ?>
        <div class="w3-panel w3-pale-green w3-border w3-round w3-padding"><?php echo $mensaje; ?></div>
    <?php endif; ?>

    <?php if (empty($pacientes)): ?>
        <p class="w3-text-grey">Primero debes <a href="registro_pacientes.php">registrar una mascota</a> antes de programar una cirugía.</p>
    <?php else: ?>
    <form method="POST">
        <div class="w3-row-padding">
            <div class="w3-col m6">
                <label class="w3-text-grey"><b>Mascota</b></label>
                <select class="w3-select w3-border w3-round" name="id_paciente" required>
                    <option value="" disabled selected>Selecciona una mascota</option>
                    <?php foreach ($pacientes as $p): ?>
                        <option value="<?php echo $p['id_paciente']; ?>">
                            <?php echo htmlspecialchars($p['nombre_mascota'] . ' (' . $p['especie'] . ')'); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="w3-col m6">
                <label class="w3-text-grey"><b>Tipo de Cirugía</b></label>
                <input class="w3-input w3-border w3-round" type="text" name="tipo_cirugia" placeholder="Esterilización, extracción dental..." required>
            </div>
        </div>
        <div class="w3-row-padding w3-margin-top">
            <div class="w3-col m6">
                <label class="w3-text-grey"><b>Fecha y Hora Programada</b></label>
                <input class="w3-input w3-border w3-round" type="datetime-local" name="fecha_programada" required>
            </div>
        </div>
        <div class="w3-margin-top">
            <button type="submit" class="w3-button w3-round w3-padding btn-vet"><i class="fa fa-save"></i>&nbsp; Programar</button>
        </div>
    </form>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>
