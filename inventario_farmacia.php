<<?php
/**
 * inventario_farmacia.php
 * Lista el inventario de medicamentos de la farmacia.
 */
require_once 'conexion.php';
$tituloPagina = "Inventario de Farmacia";

$pdo = conectarDB();
$medicamentos = $pdo->query(
    "SELECT * FROM farmacia ORDER BY nombre_medicamento ASC"
)->fetchAll();

include 'includes/header.php';
?>

<div class="w3-container w3-card w3-white w3-round w3-padding-large">
    <h2 class="w3-border-bottom w3-padding-bottom" style="color:#2c6e63;">
        <i class="fa fa-eyedropper"></i>&nbsp; Inventario de Farmacia
        <a href="vender_medicamento.php" class="w3-button w3-round w3-right w3-small w3-margin-left" style="background-color:#3f9a89; color:#fff;">
            <i class="fa fa-shopping-cart"></i>&nbsp; Vender
        </a>
        <a href="entrada_medicamentos.php" class="w3-button w3-round w3-right w3-small btn-vet">
            <i class="fa fa-plus"></i>&nbsp; Entrada de Medicamentos
        </a>
    </h2>

    <?php if (empty($medicamentos)): ?>
        <p class="w3-text-grey w3-center w3-padding-32">No hay medicamentos registrados en el inventario.</p>
    <?php else: ?>
    <div style="overflow-x:auto;">
    <table class="w3-table w3-striped w3-bordered w3-hoverable">
        <thead>
            <tr class="w3-dark-grey"><th>Medicamento</th><th>Stock</th><th>Vencimiento</th><th>Ingresado</th></tr>
        </thead>
        <tbody>
        <?php foreach ($medicamentos as $m):
            $bajo = $m['stock'] <= 5;
        ?>
            <tr class="<?php echo $bajo ? 'w3-pale-red' : ''; ?>">
                <td><b><?php echo htmlspecialchars($m['nombre_medicamento']); ?></b></td>
                <td><?php echo (int) $m['stock']; ?> <?php echo $bajo ? '<span class="w3-tag w3-round w3-red w3-small">Stock bajo</span>' : ''; ?></td>
                <td><?php echo $m['fecha_vencimiento'] ? date('d/m/Y', strtotime($m['fecha_vencimiento'])) : '—'; ?></td>
                <td><?php echo date('d/m/Y', strtotime($m['fecha_ingreso'])); ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    </div>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>