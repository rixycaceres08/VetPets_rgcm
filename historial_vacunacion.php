<?php

require_once 'conexion.php';
$tituloPagina = "Historial de Vacunación";

$pdo = conectarDB();
$stmt = $pdo->query(
    "SELECT v.id_vacuna, v.nombre_vacuna, v.fecha_aplicacion, v.proxima_dosis, p.nombre_mascota
     FROM vacunas v
     JOIN pacientes p ON p.id_paciente = v.id_paciente
     ORDER BY v.fecha_aplicacion DESC"
);
$vacunas = $stmt->fetchAll();

include 'includes/header.php';
?>

<div class="w3-container w3-card w3-white w3-round w3-padding-large">
    <h2 class="w3-border-bottom w3-padding-bottom" style="color:#2c6e63;">
        <i class="fa fa-flask"></i>&nbsp; Historial de Vacunación
        <a href="registrar_vacuna.php" class="w3-button w3-round w3-right w3-small btn-vet">
            <i class="fa fa-plus"></i>&nbsp; Registrar Nueva Vacuna
        </a>
    </h2>

    <?php if (isset($_GET['actualizado'])): ?>
        <div class="w3-panel w3-pale-green w3-border w3-round w3-padding">
            <i class="fa fa-check-circle"></i>&nbsp; Vacuna actualizada correctamente.
        </div>
    <?php endif; ?>

    <?php if (empty($vacunas)): ?>
        <p class="w3-text-grey w3-center w3-padding-32">No hay vacunas registradas todavía.</p>
    <?php else: ?>
    <div style="overflow-x:auto;">
    <table class="w3-table w3-striped w3-bordered w3-hoverable">
        <thead>
            <tr class="w3-dark-grey"><th>Mascota</th><th>Vacuna</th><th>Fecha de Aplicación</th><th>Próxima Dosis</th><th>Acciones</th></tr>
        </thead>
        <tbody>
        <?php foreach ($vacunas as $v): ?>
            <tr>
                <td><b><?php echo htmlspecialchars($v['nombre_mascota']); ?></b></td>
                <td><?php echo htmlspecialchars($v['nombre_vacuna']); ?></td>
                <td><?php echo $v['fecha_aplicacion'] ? date('d/m/Y', strtotime($v['fecha_aplicacion'])) : '—'; ?></td>
                <td><?php echo $v['proxima_dosis'] ? date('d/m/Y', strtotime($v['proxima_dosis'])) : '—'; ?></td>
                <td>
                    <a href="editar_vacuna.php?id=<?php echo $v['id_vacuna']; ?>" class="w3-button w3-small w3-round btn-vet" title="Editar fechas">
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
