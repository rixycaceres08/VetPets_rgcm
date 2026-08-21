<?php

require_once 'conexion.php';
$tituloPagina = "Datos de la Clínica";

$pdo = conectarDB();
$mensaje = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $pdo->prepare(
        "UPDATE clinica_config
            SET nombre_clinica = :nombre, direccion = :direccion,
                telefono = :telefono, correo = :correo, horario_atencion = :horario
          WHERE id_config = 1"
    );
    $stmt->execute([
        ':nombre'    => trim($_POST['nombre_clinica']),
        ':direccion' => trim($_POST['direccion']),
        ':telefono'  => trim($_POST['telefono']),
        ':correo'    => trim($_POST['correo']),
        ':horario'   => trim($_POST['horario_atencion']),
    ]);
    $mensaje = "Datos de la clínica actualizados correctamente.";
}

$config = $pdo->query("SELECT * FROM clinica_config LIMIT 1")->fetch();

include 'includes/header.php';
?>

<div class="w3-container w3-card w3-white w3-round w3-padding-large">
    <h2 class="w3-border-bottom w3-padding-bottom" style="color:#2c6e63;">
        <i class="fa fa-hospital-o"></i>&nbsp; Datos de la Clínica
    </h2>

    <?php if ($mensaje): ?>
        <div class="w3-panel w3-pale-green w3-border w3-round w3-padding"><?php echo $mensaje; ?></div>
    <?php endif; ?>

    <?php if (!$config): ?>
        <p class="w3-text-grey">No se encontró configuración inicial. Ejecuta el script <code>vetpets_db.sql</code> para crearla.</p>
    <?php else: ?>
    <form method="POST">
        <div class="w3-row-padding">
            <div class="w3-col m6">
                <label class="w3-text-grey"><b>Nombre de la Clínica</b></label>
                <input class="w3-input w3-border w3-round" type="text" name="nombre_clinica"
                       value="<?php echo htmlspecialchars($config['nombre_clinica']); ?>" required>
            </div>
            <div class="w3-col m6">
                <label class="w3-text-grey"><b>Teléfono</b></label>
                <input class="w3-input w3-border w3-round" type="text" name="telefono"
                       value="<?php echo htmlspecialchars($config['telefono']); ?>">
            </div>
        </div>
        <div class="w3-row-padding w3-margin-top">
            <div class="w3-col m6">
                <label class="w3-text-grey"><b>Correo</b></label>
                <input class="w3-input w3-border w3-round" type="email" name="correo"
                       value="<?php echo htmlspecialchars($config['correo']); ?>">
            </div>
            <div class="w3-col m6">
                <label class="w3-text-grey"><b>Horario de Atención</b></label>
                <input class="w3-input w3-border w3-round" type="text" name="horario_atencion"
                       value="<?php echo htmlspecialchars($config['horario_atencion']); ?>">
            </div>
        </div>
        <div class="w3-row-padding w3-margin-top">
            <div class="w3-col m12">
                <label class="w3-text-grey"><b>Dirección</b></label>
                <input class="w3-input w3-border w3-round" type="text" name="direccion"
                       value="<?php echo htmlspecialchars($config['direccion']); ?>">
            </div>
        </div>
        <div class="w3-margin-top">
            <button type="submit" class="w3-button w3-round w3-padding btn-vet"><i class="fa fa-save"></i>&nbsp; Guardar Cambios</button>
        </div>
    </form>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>
