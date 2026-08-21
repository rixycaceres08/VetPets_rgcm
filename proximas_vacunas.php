<?php

require_once 'conexion.php';
$tituloPagina = "Próximas Vacunas";

$pdo = conectarDB();
$stmt = $pdo->prepare(
    "SELECT v.nombre_vacuna, v.proxima_dosis, p.nombre_mascota, p.dni_propietario
     FROM vacunas v
     JOIN pacientes p ON p.id_paciente = v.id_paciente
     WHERE v.proxima_dosis IS NOT NULL AND v.proxima_dosis >= :hoy
     ORDER BY v.proxima_dosis ASC"
);
$stmt->execute([':hoy' => date('Y-m-d')]);
$proximas = $stmt->fetchAll();

include 'includes/header.php';
?>

<div class="w3-container w3-card w3-white w3-round w3-padding-large">
    <h2 class="w3-border-bottom w3-padding-bottom" style="color:#2c6e63;">
        <i class="fa fa-clock-o"></i>&nbsp; Próximas Vacunas
        <a href="registrar_vacuna.php" class="w3-button w3-round w3-right w3-small btn-vet">
            <i class="fa fa-plus"></i>&nbsp; Registrar Vacuna
        </a>
    </h2>

    <?php if (empty($proximas)): ?>
        <p class="w3-text-grey w3-center w3-padding-32">No hay próximas dosis pendientes registradas.</p>
    <?php else: ?>
    <div style="overflow-x:auto;">
    <table class="w3-table w3-striped w3-bordered w3-hoverable">
        <thead>
            <tr class="w3-dark-grey"><th>Mascota</th><th>DNI Propietario</th><th>Vacuna</th><th>Próxima Dosis</th></tr>
        </thead>
        <tbody>
        <?php foreach ($proximas as $v):
            $dias = (strtotime($v['proxima_dosis']) - strtotime(date('Y-m-d'))) / 86400;
            $urgente = $dias <= 7;
        ?>
            <tr class="<?php echo $urgente ? 'w3-pale-yellow' : ''; ?>">
                <td><b><?php echo htmlspecialchars($v['nombre_mascota']); ?></b></td>
                <td><?php echo htmlspecialchars($v['dni_propietario']); ?></td>
                <td><?php echo htmlspecialchars($v['nombre_vacuna']); ?></td>
                <td><?php echo date('d/m/Y', strtotime($v['proxima_dosis'])); ?>
                    <?php if ($urgente): ?><span class="w3-tag w3-round w3-yellow w3-small">Próxima</span><?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    </div>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>

