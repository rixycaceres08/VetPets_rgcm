<?php

require_once 'conexion.php';
$tituloPagina = "Calendario de Cirugías";

$pdo = conectarDB();
$stmt = $pdo->query(
    "SELECT c.id_cirugia, c.tipo_cirugia, c.fecha_programada, c.estado,
            p.nombre_mascota
     FROM cirugias c
     JOIN pacientes p ON p.id_paciente = c.id_paciente
     ORDER BY c.fecha_programada ASC"
);
$cirugias = $stmt->fetchAll();

include 'includes/header.php';
?>

<div class="w3-container w3-card w3-white w3-round w3-padding-large">
    <h2 class="w3-border-bottom w3-padding-bottom" style="color:#2c6e63;">
        <i class="fa fa-calendar"></i>&nbsp; Calendario de Cirugías
        <a href="programar_cirugia.php" class="w3-button w3-round w3-right w3-small btn-vet">
            <i class="fa fa-plus"></i>&nbsp; Programar Cirugía
        </a>
    </h2>

    <?php if (isset($_GET['actualizado'])): ?>
        <div class="w3-panel w3-pale-green w3-border w3-round w3-padding">
            <i class="fa fa-check-circle"></i>&nbsp; Cirugía actualizada correctamente.
        </div>
    <?php endif; ?>

    <?php if (empty($cirugias)): ?>
        <p class="w3-text-grey w3-center w3-padding-32">No hay cirugías registradas todavía.</p>
    <?php else: ?>
    <div style="overflow-x:auto;">
    <table class="w3-table w3-striped w3-bordered w3-hoverable">
        <thead>
            <tr class="w3-dark-grey">
                <th>Mascota</th><th>Tipo de Cirugía</th><th>Fecha Programada</th><th>Estado</th><th>Acciones</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($cirugias as $c): ?>
            <tr>
                <td><b><?php echo htmlspecialchars($c['nombre_mascota']); ?></b></td>
                <td><?php echo htmlspecialchars($c['tipo_cirugia']); ?></td>
                <td><?php echo $c['fecha_programada'] ? date('d/m/Y H:i', strtotime($c['fecha_programada'])) : '—'; ?></td>
                <td>
                    <?php
                        $color = $c['estado'] === 'Realizada' ? 'w3-pale-green' : ($c['estado'] === 'Cancelada' ? 'w3-pale-red' : 'w3-pale-yellow');
                    ?>
                    <span class="w3-tag w3-round <?php echo $color; ?>"><?php echo htmlspecialchars($c['estado']); ?></span>
                </td>
                <td>
                    <a href="editar_cirugia.php?id=<?php echo $c['id_cirugia']; ?>" class="w3-button w3-small w3-round btn-vet" title="Editar fecha / tipo / estado">
                        <i class="fa fa-pencil"></i>&nbsp; Editar
                    </a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    </div>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>
