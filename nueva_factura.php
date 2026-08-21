<?php
/**
 * nueva_factura.php
 * Formulario para generar una nueva factura de un propietario.
 */
require_once 'conexion.php';
$tituloPagina = "Nueva Factura";

$pdo = conectarDB();
$mensaje = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $pdo->prepare(
        "INSERT INTO facturas (dni_propietario, monto_total, estado_pago)
         VALUES (:dni, :monto, :estado)"
    );
    $stmt->execute([
        ':dni'    => trim($_POST['dni_propietario']),
        ':monto'  => $_POST['monto_total'],
        ':estado' => $_POST['estado_pago'],
    ]);
    $mensaje = "Factura registrada correctamente.";
}

$propietarios = $pdo->query("SELECT dni, nombre_completo FROM propietarios ORDER BY nombre_completo ASC")->fetchAll();

include 'includes/header.php';
?>

<div class="w3-container w3-card w3-white w3-round w3-padding-large">
    <h2 class="w3-border-bottom w3-padding-bottom" style="color:#2c6e63;">
        <i class="fa fa-file-text"></i>&nbsp; Nueva Factura
        <a href="historial_facturas.php" class="w3-button w3-round w3-right w3-small btn-vet">
            <i class="fa fa-list"></i>&nbsp; Ver Historial
        </a>
    </h2>

    <?php if ($mensaje): ?>
        <div class="w3-panel w3-pale-green w3-border w3-round w3-padding"><?php echo $mensaje; ?></div>
    <?php endif; ?>

    <?php if (empty($propietarios)): ?>
        <p class="w3-text-grey">Primero debes <a href="registro_pacientes.php">registrar un paciente</a> (esto crea automáticamente al propietario).</p>
    <?php else: ?>
    <form method="POST">
        <div class="w3-row-padding">
            <div class="w3-col m6">
                <label class="w3-text-grey"><b>Propietario</b></label>
                <select class="w3-select w3-border w3-round" name="dni_propietario" required>
                    <option value="" disabled selected>Selecciona un propietario</option>
                    <?php foreach ($propietarios as $p): ?>
                        <option value="<?php echo htmlspecialchars($p['dni']); ?>">
                            <?php echo htmlspecialchars($p['nombre_completo'] . ' — DNI: ' . $p['dni']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="w3-col m6">
                <label class="w3-text-grey"><b>Monto Total (L.)</b></label>
                <input class="w3-input w3-border w3-round" type="number" step="0.01" min="0" name="monto_total" required>
            </div>
        </div>
        <div class="w3-row-padding w3-margin-top">
            <div class="w3-col m6">
                <label class="w3-text-grey"><b>Estado de Pago</b></label>
                <select class="w3-select w3-border w3-round" name="estado_pago">
                    <option value="Pendiente">Pendiente</option>
                    <option value="Pagada">Pagada</option>
                </select>
            </div>
        </div>
        <div class="w3-margin-top">
            <button type="submit" class="w3-button w3-round w3-padding btn-vet"><i class="fa fa-save"></i>&nbsp; Generar Factura</button>
        </div>
    </form>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>