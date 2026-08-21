<?php

require_once 'conexion.php';
$tituloPagina = "Pagos Pendientes";

$pdo = conectarDB();
$mensaje = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_factura'])) {
    $stmt = $pdo->prepare("UPDATE facturas SET estado_pago = 'Pagada' WHERE id_factura = :id");
    $stmt->execute([':id' => (int) $_POST['id_factura']]);
    $mensaje = "Factura #" . (int) $_POST['id_factura'] . " marcada como pagada.";
}

$stmt = $pdo->query(
    "SELECT f.id_factura, f.monto_total, f.fecha_emision, pr.nombre_completo, f.dni_propietario
     FROM facturas f
     LEFT JOIN propietarios pr ON pr.dni = f.dni_propietario
     WHERE f.estado_pago = 'Pendiente'
     ORDER BY f.fecha_emision ASC"
);
$pendientes = $stmt->fetchAll();

include 'includes/header.php';
?>

<div class="w3-container w3-card w3-white w3-round w3-padding-large">
    <h2 class="w3-border-bottom w3-padding-bottom" style="color:#2c6e63;">
        <i class="fa fa-clock-o"></i>&nbsp; Pagos Pendientes
    </h2>

    <?php if ($mensaje): ?>
        <div class="w3-panel w3-pale-green w3-border w3-round w3-padding"><?php echo $mensaje; ?></div>
    <?php endif; ?>

    <?php if (empty($pendientes)): ?>
        <p class="w3-text-grey w3-center w3-padding-32">No hay pagos pendientes. ¡Todo al día!</p>
    <?php else: ?>
    <div style="overflow-x:auto;">
    <table class="w3-table w3-striped w3-bordered w3-hoverable">
        <thead>
            <tr class="w3-dark-grey"><th>#</th><th>Propietario</th><th>DNI</th><th>Monto (L.)</th><th>Fecha</th><th></th></tr>
        </thead>
        <tbody>
        <?php foreach ($pendientes as $f): ?>
            <tr>
                <td><?php echo $f['id_factura']; ?></td>
                <td><?php echo htmlspecialchars($f['nombre_completo'] ?: 'Sin especificar'); ?></td>
                <td><?php echo htmlspecialchars($f['dni_propietario']); ?></td>
                <td><?php echo number_format($f['monto_total'], 2); ?></td>
                <td><?php echo date('d/m/Y', strtotime($f['fecha_emision'])); ?></td>
                <td>
                    <form method="POST">
                        <input type="hidden" name="id_factura" value="<?php echo $f['id_factura']; ?>">
                        <button type="submit" class="w3-button w3-small w3-round btn-vet">
                            <i class="fa fa-check"></i>&nbsp; Marcar como Pagada
                        </button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    </div>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>
