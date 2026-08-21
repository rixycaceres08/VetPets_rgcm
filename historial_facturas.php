<?php

require_once 'conexion.php';
$tituloPagina = "Historial de Facturas";

$pdo = conectarDB();
$stmt = $pdo->query(
    "SELECT f.id_factura, f.monto_total, f.estado_pago, f.fecha_emision,
            pr.nombre_completo, f.dni_propietario
     FROM facturas f
     LEFT JOIN propietarios pr ON pr.dni = f.dni_propietario
     ORDER BY f.fecha_emision DESC"
);
$facturas = $stmt->fetchAll();

include 'includes/header.php';
?>

<div class="w3-container w3-card w3-white w3-round w3-padding-large">
    <h2 class="w3-border-bottom w3-padding-bottom" style="color:#2c6e63;">
        <i class="fa fa-file-text"></i>&nbsp; Historial de Facturas
        <a href="nueva_factura.php" class="w3-button w3-round w3-right w3-small btn-vet">
            <i class="fa fa-plus"></i>&nbsp; Nueva Factura
        </a>
    </h2>

    <?php if (empty($facturas)): ?>
        <p class="w3-text-grey w3-center w3-padding-32">No hay facturas registradas.</p>
    <?php else: ?>
    <div style="overflow-x:auto;">
    <table class="w3-table w3-striped w3-bordered w3-hoverable">
        <thead>
            <tr class="w3-dark-grey"><th>#</th><th>Propietario</th><th>DNI</th><th>Monto (L.)</th><th>Estado</th><th>Fecha</th></tr>
        </thead>
        <tbody>
        <?php foreach ($facturas as $f): ?>
            <tr>
                <td><?php echo $f['id_factura']; ?></td>
                <td><?php echo htmlspecialchars($f['nombre_completo'] ?: 'Sin especificar'); ?></td>
                <td><?php echo htmlspecialchars($f['dni_propietario']); ?></td>
                <td><?php echo number_format($f['monto_total'], 2); ?></td>
                <td>
                    <span class="w3-tag w3-round <?php echo $f['estado_pago'] === 'Pagada' ? 'w3-pale-green' : 'w3-pale-yellow'; ?>">
                        <?php echo htmlspecialchars($f['estado_pago']); ?>
                    </span>
                </td>
                <td><?php echo date('d/m/Y', strtotime($f['fecha_emision'])); ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    </div>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>
