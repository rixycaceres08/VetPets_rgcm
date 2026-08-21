<?php

require_once 'conexion.php';
$tituloPagina = "Medicamentos por Vencer";

$pdo = conectarDB();
$diasLimite = 60;

$stmt = $pdo->prepare(
    "SELECT * FROM farmacia
     WHERE fecha_vencimiento IS NOT NULL
       AND fecha_vencimiento <= DATE_ADD(:hoy, INTERVAL :dias DAY)
     ORDER BY fecha_vencimiento ASC"
);
$stmt->bindValue(':hoy', date('Y-m-d'));
$stmt->bindValue(':dias', $diasLimite, PDO::PARAM_INT);
$stmt->execute();
$medicamentos = $stmt->fetchAll();

include 'includes/header.php';
?>

<div class="w3-container w3-card w3-white w3-round w3-padding-large">
    <h2 class="w3-border-bottom w3-padding-bottom" style="color:#2c6e63;">
        <i class="fa fa-exclamation-triangle"></i>&nbsp; Medicamentos por Vencer
    </h2>
    <p class="w3-text-grey">Medicamentos que vencen en los próximos <?php echo $diasLimite; ?> días.</p>

    <?php if (empty($medicamentos)): ?>
        <p class="w3-text-grey w3-center w3-padding-32">No hay medicamentos próximos a vencer.</p>
    <?php else: ?>
    <div style="overflow-x:auto;">
    <table class="w3-table w3-striped w3-bordered w3-hoverable">
        <thead><tr class="w3-dark-grey"><th>Medicamento</th><th>Stock</th><th>Vencimiento</th></tr></thead>
        <tbody>
        <?php foreach ($medicamentos as $m):
            $vencido = strtotime($m['fecha_vencimiento']) < strtotime(date('Y-m-d'));
        ?>
            <tr class="<?php echo $vencido ? 'w3-pale-red' : 'w3-pale-yellow'; ?>">
                <td><b><?php echo htmlspecialchars($m['nombre_medicamento']); ?></b></td>
                <td><?php echo (int) $m['stock']; ?></td>
                <td>
                    <?php echo date('d/m/Y', strtotime($m['fecha_vencimiento'])); ?>
                    <?php echo $vencido ? '<span class="w3-tag w3-round w3-red w3-small">Vencido</span>' : '<span class="w3-tag w3-round w3-yellow w3-small">Por vencer</span>'; ?>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    </div>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>