<?php

require_once 'conexion.php';
$tituloPagina = "Panel Principal";

$pdo = conectarDB();
$totalPacientes = (int) $pdo->query("SELECT COUNT(*) FROM pacientes")->fetchColumn();
$cirugiasProgramadas = (int) $pdo->query(
    "SELECT COUNT(*) FROM cirugias WHERE estado = 'Programada'"
)->fetchColumn();

$vacunasProximas = (int) $pdo->query(
    "SELECT COUNT(*) FROM vacunas WHERE proxima_dosis IS NOT NULL AND proxima_dosis >= CURDATE()"
)->fetchColumn();

$facturasPendientes = (int) $pdo->query(
    "SELECT COUNT(*) FROM facturas WHERE estado_pago = 'Pendiente'"
)->fetchColumn();

$medicamentosPorVencer = (int) $pdo->query(
    "SELECT COUNT(*) FROM farmacia WHERE fecha_vencimiento IS NOT NULL AND fecha_vencimiento <= DATE_ADD(CURDATE(), INTERVAL 60 DAY)"
)->fetchColumn();

$ultimosPacientes = $pdo->query(
    "SELECT nombre_mascota, especie, fecha_registro FROM pacientes ORDER BY id_paciente DESC LIMIT 5"
)->fetchAll();

$hora = (int) date('H');
if ($hora < 12) {
    $saludo = "Buenos días";
} elseif ($hora < 19) {
    $saludo = "Buenas tardes";
} else {
    $saludo = "Buenas noches";
}

include 'includes/header.php';
?>

<div class="w3-container w3-card w3-round w3-margin-bottom" style="padding:28px 24px; background:linear-gradient(135deg,#2c6e63,#3f9a89); color:#fff;">
    <i class="fa fa-paw" style="font-size:38px; opacity:.85;"></i>
    <h2 style="margin:8px 0 4px;"><?php echo $saludo; ?>, <?php echo $nombreAdmin; ?> 👋</h2>
    <p style="margin:0; opacity:.9;">Este es el resumen general de <?php echo $nombreClinica; ?> hoy, <?php echo date('d/m/Y'); ?>.</p>
</div>

<div class="w3-row-padding w3-margin-bottom">
    <div class="w3-col m3 s6 w3-margin-bottom">
        <div class="w3-card w3-white w3-round w3-padding w3-center">
            <i class="fa fa-paw" style="font-size:28px; color:#2c6e63;"></i>
            <h2 style="margin:8px 0 0;"><?php echo $totalPacientes; ?></h2>
            <p class="w3-text-grey w3-small" style="margin:0;">Mascotas registradas</p>
        </div>
    </div>
    <div class="w3-col m3 s6 w3-margin-bottom">
        <div class="w3-card w3-white w3-round w3-padding w3-center">
            <i class="fa fa-medkit" style="font-size:28px; color:#c77d2b;"></i>
            <h2 style="margin:8px 0 0;"><?php echo $cirugiasProgramadas; ?></h2>
            <p class="w3-text-grey w3-small" style="margin:0;">Cirugías programadas</p>
        </div>
    </div>
    <div class="w3-col m3 s6 w3-margin-bottom">
        <div class="w3-card w3-white w3-round w3-padding w3-center">
            <i class="fa fa-flask" style="font-size:28px; color:#3f74c7;"></i>
            <h2 style="margin:8px 0 0;"><?php echo $vacunasProximas; ?></h2>
            <p class="w3-text-grey w3-small" style="margin:0;">Próximas vacunas</p>
        </div>
    </div>
    <div class="w3-col m3 s6 w3-margin-bottom">
        <div class="w3-card w3-white w3-round w3-padding w3-center">
            <i class="fa fa-file-text" style="font-size:28px; color:#c73f3f;"></i>
            <h2 style="margin:8px 0 0;"><?php echo $facturasPendientes; ?></h2>
            <p class="w3-text-grey w3-small" style="margin:0;">Facturas pendientes</p>
        </div>
    </div>
</div>

<div class="w3-row-padding">
    <div class="w3-col m7 w3-margin-bottom">
        <div class="w3-container w3-card w3-white w3-round w3-padding-large" style="height:100%;">
            <h3 style="color:#2c6e63;"><i class="fa fa-bolt"></i>&nbsp; Accesos Rápidos</h3>
            <div class="w3-row-padding">
                <div class="w3-col m6 w3-margin-bottom">
                    <a href="registro_pacientes.php" class="w3-button w3-block w3-round w3-padding btn-vet">
                        <i class="fa fa-plus"></i>&nbsp; Registrar Paciente
                    </a>
                </div>
                <div class="w3-col m6 w3-margin-bottom">
                    <a href="listar_pacientes.php" class="w3-button w3-block w3-round w3-padding w3-light-grey">
                        <i class="fa fa-list"></i>&nbsp; Ver Lista de Mascotas
                    </a>
                </div>
                <div class="w3-col m6 w3-margin-bottom">
                    <a href="programar_cirugia.php" class="w3-button w3-block w3-round w3-padding w3-light-grey">
                        <i class="fa fa-medkit"></i>&nbsp; Programar Cirugía
                    </a>
                </div>
                <div class="w3-col m6 w3-margin-bottom">
                    <a href="registrar_vacuna.php" class="w3-button w3-block w3-round w3-padding w3-light-grey">
                        <i class="fa fa-flask"></i>&nbsp; Registrar Vacuna
                    </a>
                </div>
                <div class="w3-col m6 w3-margin-bottom">
                    <a href="nueva_factura.php" class="w3-button w3-block w3-round w3-padding w3-light-grey">
                        <i class="fa fa-file-text"></i>&nbsp; Nueva Factura
                    </a>
                </div>
                <div class="w3-col m6 w3-margin-bottom">
                    <a href="inventario_farmacia.php" class="w3-button w3-block w3-round w3-padding w3-light-grey">
                        <i class="fa fa-eyedropper"></i>&nbsp; Ver Farmacia
                    </a>
                </div>
            </div>

            <?php if ($medicamentosPorVencer > 0): ?>
            <div class="w3-panel w3-pale-yellow w3-border w3-round w3-padding w3-margin-top">
                <i class="fa fa-exclamation-triangle"></i>
                Hay <b><?php echo $medicamentosPorVencer; ?></b> medicamento(s) próximos a vencer.
                <a href="medicamentos_vencer.php" class="w3-margin-left"><b>Revisar &rarr;</b></a>
            </div>
            <?php endif; ?>
        </div>
    </div>
    <div class="w3-col m5 w3-margin-bottom">
        <div class="w3-container w3-card w3-white w3-round w3-padding-large" style="height:100%;">
            <h3 style="color:#2c6e63;"><i class="fa fa-clock-o"></i>&nbsp; Últimos Pacientes Registrados</h3>
            <?php if (empty($ultimosPacientes)): ?>
                <p class="w3-text-grey">Aún no hay pacientes registrados.</p>
            <?php else: ?>
                <ul class="w3-ul">
                    <?php foreach ($ultimosPacientes as $up): ?>
                        <li>
                            <b><?php echo htmlspecialchars($up['nombre_mascota']); ?></b>
                            <span class="w3-text-grey"> — <?php echo htmlspecialchars($up['especie']); ?></span>
                            <span class="w3-right w3-small w3-text-grey"><?php echo date('d/m/Y', strtotime($up['fecha_registro'])); ?></span>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>