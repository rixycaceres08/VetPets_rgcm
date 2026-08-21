<?php

require_once 'conexion.php';
$tituloPagina = "Entrada de Medicamentos";

$pdo = conectarDB();
$mensaje = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre_medicamento']);
    $cantidad = (int) $_POST['stock'];
    $vencimiento = $_POST['fecha_vencimiento'] !== '' ? $_POST['fecha_vencimiento'] : null;

    // ¿Ya existe el medicamento?
    $stmt = $pdo->prepare("SELECT id_medicamento, stock FROM farmacia WHERE nombre_medicamento = :nombre LIMIT 1");
    $stmt->execute([':nombre' => $nombre]);
    $existente = $stmt->fetch();

    if ($existente) {
        $stmt = $pdo->prepare(
            "UPDATE farmacia
                SET stock = stock + :cantidad,
                    fecha_vencimiento = :vencimiento
              WHERE id_medicamento = :id"
        );
        $stmt->execute([
            ':cantidad'    => $cantidad,
            ':vencimiento' => $vencimiento,
            ':id'          => $existente['id_medicamento'],
        ]);
        $mensaje = "Stock actualizado: +$cantidad unidades de $nombre.";
    } else {
        $stmt = $pdo->prepare(
            "INSERT INTO farmacia (nombre_medicamento, stock, fecha_vencimiento)
             VALUES (:nombre, :cantidad, :vencimiento)"
        );
        $stmt->execute([
            ':nombre'      => $nombre,
            ':cantidad'    => $cantidad,
            ':vencimiento' => $vencimiento,
        ]);
        $mensaje = "Medicamento $nombre agregado al inventario.";
    }
}

include 'includes/header.php';
?>

<div class="w3-container w3-card w3-white w3-round w3-padding-large">
    <h2 class="w3-border-bottom w3-padding-bottom" style="color:#2c6e63;">
        <i class="fa fa-plus-square"></i>&nbsp; Entrada de Medicamentos
        <a href="inventario_farmacia.php" class="w3-button w3-round w3-right w3-small btn-vet">
            <i class="fa fa-list"></i>&nbsp; Ver Inventario
        </a>
    </h2>

    <?php if ($mensaje): ?>
        <div class="w3-panel w3-pale-green w3-border w3-round w3-padding"><?php echo $mensaje; ?></div>
    <?php endif; ?>

    <form method="POST">
        <div class="w3-row-padding">
            <div class="w3-col m6">
                <label class="w3-text-grey"><b>Nombre del Medicamento</b></label>
                <input class="w3-input w3-border w3-round" type="text" name="nombre_medicamento" required>
            </div>
            <div class="w3-col m6">
                <label class="w3-text-grey"><b>Cantidad a Ingresar</b></label>
                <input class="w3-input w3-border w3-round" type="number" min="1" name="stock" required>
            </div>
        </div>
        <div class="w3-row-padding w3-margin-top">
            <div class="w3-col m6">
                <label class="w3-text-grey"><b>Fecha de Vencimiento</b></label>
                <input class="w3-input w3-border w3-round" type="date" name="fecha_vencimiento">
            </div>
        </div>
        <div class="w3-margin-top">
            <button type="submit" class="w3-button w3-round w3-padding btn-vet"><i class="fa fa-save"></i>&nbsp; Registrar Entrada</button>
        </div>
    </form>
</div>

<?php include 'includes/footer.php'; ?>