<?php

require_once 'conexion.php';
$tituloPagina = "Seguimiento Postoperatorio";

$pdo = conectarDB();
$mensaje = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $pdo->prepare(
        "UPDATE cirugias
            SET estado = 'Realizada',
                seguimiento_postoperatorio = :notas
          WHERE id_cirugia = :id"
    );
    $stmt->execute([
        ':notas' => trim($_POST['seguimiento_postoperatorio']),
        ':id'    => (int) $_POST['id_cirugia'],
    ]);
    $mensaje = "Seguimiento postoperatorio actualizado.";
}

$stmt = $pdo->query(
    "SELECT c.id_cirugia, c.tipo_cirugia, c.fecha_programada, c.estado,
            c.seguimiento_postoperatorio, p.nombre_mascota
     FROM cirugias c
     JOIN pacientes p ON p.id_paciente = c.id_paciente
     ORDER BY c.fecha_programada DESC"
);
$cirugias = $stmt->fetchAll();

include 'includes/header.php';
?>

<div class="w3-container w3-card w3-white w3-round w3-padding-large">
    <h2 class="w3-border-bottom w3-padding-bottom" style="color:#2c6e63;">
        <i class="fa fa-stethoscope"></i>&nbsp; Seguimiento Postoperatorio
    </h2>

    <?php if ($mensaje): ?>
        <div class="w3-panel w3-pale-green w3-border w3-round w3-padding"><?php echo $mensaje; ?></div>
    <?php endif; ?>

    <?php if (empty($cirugias)): ?>
        <p class="w3-text-grey w3-center w3-padding-32">No hay cirugías registradas.</p>
    <?php else: ?>
        <?php foreach ($cirugias as $c): ?>
        <div class="w3-card w3-round w3-margin-bottom w3-padding" style="border:1px solid #e0e0e0;">
            <div class="w3-row">
                <div class="w3-col m8">
                    <h4 style="margin:0; color:#2c6e63;"><?php echo htmlspecialchars($c['nombre_mascota']); ?> — <?php echo htmlspecialchars($c['tipo_cirugia']); ?></h4>
                    <p class="w3-small w3-text-grey" style="margin:4px 0;">
                        Fecha: <?php echo $c['fecha_programada'] ? date('d/m/Y H:i', strtotime($c['fecha_programada'])) : '—'; ?>
                        &nbsp;|&nbsp; Estado: <b><?php echo htmlspecialchars($c['estado']); ?></b>
                    </p>
                </div>
            </div>
            <form method="POST" class="w3-margin-top">
                <input type="hidden" name="id_cirugia" value="<?php echo $c['id_cirugia']; ?>">
                <label class="w3-text-grey"><b>Notas de Seguimiento Postoperatorio</b></label>
                <textarea class="w3-input w3-border w3-round w3-textarea" name="seguimiento_postoperatorio" rows="3"
                          placeholder="Evolución, medicación, próxima revisión..."><?php echo htmlspecialchars($c['seguimiento_postoperatorio'] ?? ''); ?></textarea>
                <button type="submit" class="w3-button w3-round w3-small w3-margin-top btn-vet">
                    <i class="fa fa-check"></i>&nbsp; Marcar como Realizada / Guardar Seguimiento
                </button>
            </form>
        </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>

