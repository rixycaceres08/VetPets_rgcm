<?php

require_once 'conexion.php';
$tituloPagina = "Registrar Nueva Vacuna";

$pdo = conectarDB();
$mensaje = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $pdo->prepare(
        "INSERT INTO vacunas (id_paciente, nombre_vacuna, fecha_aplicacion, proxima_dosis)
         VALUES (:id_paciente, :nombre_vacuna, :fecha_aplicacion, :proxima_dosis)"
    );
    $stmt->execute([
        ':id_paciente'      => (int) $_POST['id_paciente'],
        ':nombre_vacuna'    => trim($_POST['nombre_vacuna']),
        ':fecha_aplicacion' => $_POST['fecha_aplicacion'],
        ':proxima_dosis'    => $_POST['proxima_dosis'] !== '' ? $_POST['proxima_dosis'] : null,
    ]);
    $mensaje = "Vacuna registrada correctamente.";
}

$pacientes = $pdo->query("SELECT id_paciente, nombre_mascota, especie FROM pacientes ORDER BY nombre_mascota ASC")->fetchAll();

include 'includes/header.php';
?>

<div class="w3-container w3-card w3-white w3-round w3-padding-large">
    <h2 class="w3-border-bottom w3-padding-bottom" style="color:#2c6e63;">
        <i class="fa fa-flask"></i>&nbsp; Registrar Nueva Vacuna
        <a href="historial_vacunacion.php" class="w3-button w3-round w3-right w3-small btn-vet">
            <i class="fa fa-list"></i>&nbsp; Ver Historial
        </a>
    </h2>

    <?php if ($mensaje): ?>
        <div class="w3-panel w3-pale-green w3-border w3-round w3-padding"><?php echo $mensaje; ?></div>
    <?php endif; ?>

    <?php if (empty($pacientes)): ?>
        <p class="w3-text-grey">Primero debes <a href="registro_pacientes.php">registrar una mascota</a>.</p>
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
                <label class="w3-text-grey"><b>Nombre de la Vacuna</b></label>
                <input class="w3-input w3-border w3-round" type="text" name="nombre_vacuna" placeholder="Antirrábica, polivalente..." required>
            </div>
        </div>
        <div class="w3-row-padding w3-margin-top">
            <div class="w3-col m6">
                <label class="w3-text-grey"><b>Fecha de Aplicación</b></label>
                <input class="w3-input w3-border w3-round" type="date" name="fecha_aplicacion" required>
            </div>
            <div class="w3-col m6">
                <label class="w3-text-grey"><b>Próxima Dosis</b></label>
                <input class="w3-input w3-border w3-round" type="date" name="proxima_dosis">
            </div>
        </div>
        <div class="w3-margin-top">
            <button type="submit" class="w3-button w3-round w3-padding btn-vet"><i class="fa fa-save"></i>&nbsp; Registrar</button>
        </div>
    </form>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>

