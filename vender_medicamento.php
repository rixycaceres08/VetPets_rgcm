<?php
require_once 'conexion.php';
$tituloPagina = "Vender Medicamento";

$pdo = conectarDB();
$mensaje = "";
$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idMedicamento = (int) $_POST['id_medicamento'];
    $cantidad = (int) $_POST['cantidad'];

    if ($cantidad <= 0) {
        $error = "La cantidad a vender debe ser mayor a cero.";
    } else {
        $stmt = $pdo->prepare("SELECT nombre_medicamento, stock FROM farmacia WHERE id_medicamento = :id");
        $stmt->execute([':id' => $idMedicamento]);
        $medicamento = $stmt->fetch();

        if (!$medicamento) {
            $error = "El medicamento seleccionado no existe.";
        } elseif ($cantidad > $medicamento['stock']) {
            $error = "No hay suficiente stock de \"" . htmlspecialchars($medicamento['nombre_medicamento']) .
                     "\". Disponible: " . $medicamento['stock'] . " unidad(es).";
        } else {
            try {
                $pdo->beginTransaction();

                $stmtUpdate = $pdo->prepare(
                    "UPDATE farmacia SET stock = stock - :cantidad WHERE id_medicamento = :id"
                );
                $stmtUpdate->execute([
                    ':cantidad' => $cantidad,
                    ':id'       => $idMedicamento,
                ]);

                // 2. Registrar la venta
                $stmtVenta = $pdo->prepare(
                    "INSERT INTO ventas_farmacia (id_medicamento, cantidad_vendida) VALUES (:id, :cantidad)"
                );
                $stmtVenta->execute([
                    ':id'       => $idMedicamento,
                    ':cantidad' => $cantidad,
                ]);

                $pdo->commit();

                $mensaje = "Venta registrada: " . $cantidad . " unidad(es) de \"" .
                           htmlspecialchars($medicamento['nombre_medicamento']) . "\". Stock actualizado.";
            } catch (PDOException $e) {
                $pdo->rollBack();
                $error = "Error al registrar la venta: " . $e->getMessage();
            }
        }
    }
}

$medicamentos = $pdo->query(
    "SELECT id_medicamento, nombre_medicamento, stock FROM farmacia ORDER BY nombre_medicamento ASC"
)->fetchAll();

$ultimasVentas = $pdo->query(
    "SELECT vf.cantidad_vendida, vf.fecha_venta, f.nombre_medicamento
     FROM ventas_farmacia vf
     JOIN farmacia f ON f.id_medicamento = vf.id_medicamento
     ORDER BY vf.id_venta DESC
     LIMIT 10"
)->fetchAll();

include 'includes/header.php';
?>

<div class="w3-container w3-card w3-white w3-round w3-padding-large w3-margin-bottom">
    <h2 class="w3-border-bottom w3-padding-bottom" style="color:#2c6e63;">
        <i class="fa fa-shopping-cart"></i>&nbsp; Vender Medicamento
        <a href="inventario_farmacia.php" class="w3-button w3-round w3-right w3-small btn-vet">
            <i class="fa fa-list"></i>&nbsp; Ver Inventario
        </a>
    </h2>

    <?php if ($mensaje): ?>
        <div class="w3-panel w3-pale-green w3-border w3-round w3-padding">
            <i class="fa fa-check-circle"></i>&nbsp; <?php echo $mensaje; ?>
        </div>
    <?php endif; ?>
    <?php if ($error): ?>
        <div class="w3-panel w3-pale-red w3-border w3-round w3-padding">
            <i class="fa fa-exclamation-triangle"></i>&nbsp; <?php echo $error; ?>
        </div>
    <?php endif; ?>

    <?php if (empty($medicamentos)): ?>
        <p class="w3-text-grey">No hay medicamentos en el inventario. Agrega primero desde
            <a href="entrada_medicamentos.php">Entrada de Medicamentos</a>.</p>
    <?php else: ?>
    <form method="POST" id="formVenta">
        <div class="w3-row-padding">
            <div class="w3-col m6">
                <label class="w3-text-grey"><b>Medicamento</b></label>
                <select class="w3-select w3-border w3-round" name="id_medicamento" id="selectMedicamento" required onchange="mostrarStock()">
                    <option value="" disabled selected>Selecciona un medicamento</option>
                    <?php foreach ($medicamentos as $m): ?>
                        <option value="<?php echo $m['id_medicamento']; ?>" data-stock="<?php echo $m['stock']; ?>">
                            <?php echo htmlspecialchars($m['nombre_medicamento']); ?> (disponible: <?php echo $m['stock']; ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
                <p class="w3-small w3-text-grey" id="stockDisponible" style="margin-top:4px;"></p>
            </div>
            <div class="w3-col m6">
                <label class="w3-text-grey"><b>Cantidad a Vender</b></label>
                <input class="w3-input w3-border w3-round" type="number" min="1" name="cantidad" required>
            </div>
        </div>
        <div class="w3-margin-top">
            <button type="submit" class="w3-button w3-round w3-padding btn-vet">
                <i class="fa fa-shopping-cart"></i>&nbsp; Registrar Venta
            </button>
        </div>
    </form>
    <?php endif; ?>
</div>

<div class="w3-container w3-card w3-white w3-round w3-padding-large w3-margin-bottom">
    <h3 style="color:#2c6e63;"><i class="fa fa-cubes"></i>&nbsp; Existencias Actuales</h3>
    <?php if (empty($medicamentos)): ?>
        <p class="w3-text-grey">Sin medicamentos registrados.</p>
    <?php else: ?>
    <div style="overflow-x:auto;">
    <table class="w3-table w3-striped w3-bordered w3-hoverable">
        <thead><tr class="w3-dark-grey"><th>Medicamento</th><th>Stock Disponible</th></tr></thead>
        <tbody>
        <?php foreach ($medicamentos as $m): $bajo = $m['stock'] <= 5; ?>
            <tr class="<?php echo $bajo ? 'w3-pale-red' : ''; ?>">
                <td><b><?php echo htmlspecialchars($m['nombre_medicamento']); ?></b></td>
                <td><?php echo (int) $m['stock']; ?> <?php echo $bajo ? '<span class="w3-tag w3-round w3-red w3-small">Stock bajo</span>' : ''; ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    </div>
    <?php endif; ?>
</div>

<div class="w3-container w3-card w3-white w3-round w3-padding-large">
    <h3 style="color:#2c6e63;"><i class="fa fa-history"></i>&nbsp; Últimas Ventas</h3>
    <?php if (empty($ultimasVentas)): ?>
        <p class="w3-text-grey">Todavía no se ha registrado ninguna venta.</p>
    <?php else: ?>
    <div style="overflow-x:auto;">
    <table class="w3-table w3-striped w3-bordered w3-hoverable">
        <thead><tr class="w3-dark-grey"><th>Medicamento</th><th>Cantidad Vendida</th><th>Fecha</th></tr></thead>
        <tbody>
        <?php foreach ($ultimasVentas as $v): ?>
            <tr>
                <td><?php echo htmlspecialchars($v['nombre_medicamento']); ?></td>
                <td><?php echo (int) $v['cantidad_vendida']; ?></td>
                <td><?php echo date('d/m/Y H:i', strtotime($v['fecha_venta'])); ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    </div>
    <?php endif; ?>
</div>

<script>
function mostrarStock() {
    var select = document.getElementById('selectMedicamento');
    var opcion = select.options[select.selectedIndex];
    var stock = opcion.getAttribute('data-stock');
    document.getElementById('stockDisponible').innerText = 'Stock disponible: ' + stock + ' unidad(es)';

    var inputCantidad = document.querySelector('input[name="cantidad"]');
    inputCantidad.setAttribute('max', stock);
}
</script>

<?php include 'includes/footer.php'; ?>
