<?php

$nombreClinica = "VetPets Rixy Cáceres";
$nombreAdmin   = "Rixy Gisselle Cáceres Moncada";
if (!isset($tituloPagina)) {
    $tituloPagina = "Dashboard Administrativo";
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?php echo $nombreClinica; ?> | <?php echo $tituloPagina; ?></title>
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<style>
    body        { font-family: "Segoe UI", Arial, sans-serif; background-color: #f1f6f5; }
    .w3-sidebar { z-index: 3; width: 260px; }
    .submenu    { display: none; }
    .submenu a  { padding-left: 46px !important; font-size: 14px; }
    .menu-arrow { float: right; transition: transform 0.3s; }
    .rotate     { transform: rotate(90deg); }
    #main       { margin-left: 260px; transition: margin-left .4s; }
    .pagina-activa { background-color: #2c6e63 !important; color: #fff !important; }
    .enlace-activo {
        background-color: #d9f0ec !important;
        color: #2c6e63 !important;
        font-weight: 600;
        border-left: 4px solid #2c6e63;
        padding-left: 42px !important;
    }
    .menu-activo { color: #2c6e63 !important; font-weight: 600; }
    .w3-table td, .w3-table th { padding: 10px 12px; }
    .btn-vet    { background-color:#2c6e63; color:#fff; }
    @media screen and (max-width: 768px) {
        #main   { margin-left: 0; }
    }
</style>
</head>
<body>

<!-- ===================== BARRA LATERAL ADMINISTRATIVA ===================== -->
<nav class="w3-sidebar w3-bar-block w3-white w3-collapse w3-card" id="mySidebar">

    <div class="w3-container w3-dark-grey w3-center" style="padding:24px 12px;">
        <i class="fa fa-paw" style="font-size:48px; color:#8fd3c4;"></i>
        <h3 class="w3-margin-top" style="color:#fff; margin-bottom:2px;">
            <?php echo $nombreClinica; ?>
        </h3>
        <p class="w3-small w3-text-light-grey" style="margin-top:4px;">
            Admin: <?php echo $nombreAdmin; ?>
        </p>
        <a href="javascript:void(0)" class="w3-hide-large w3-button w3-topbar w3-border-bottom w3-right"
           onclick="cerrarSidebar()">Cerrar &times;</a>
    </div>

    <a href="dashboard_vet.php" class="w3-bar-item w3-button w3-padding w3-hover-blue" id="link-panel">
        <i class="fa fa-home w3-margin-right"></i>Panel Principal
    </a>

    <!-- Mascotas -->
    <button class="w3-bar-item w3-button w3-padding w3-hover-blue" onclick="toggleSubmenu('sub-mascotas', this)">
        <i class="fa fa-heart w3-margin-right"></i>Mascotas
        <i class="fa fa-chevron-right menu-arrow" id="arrow-sub-mascotas"></i>
    </button>
    <div id="sub-mascotas" class="submenu w3-bar-block">
        <a href="listar_pacientes.php" class="w3-bar-item w3-button w3-hover-blue">Lista de Mascotas</a>
        <a href="registro_pacientes.php" class="w3-bar-item w3-button w3-hover-blue">Registro de Pacientes</a>
        <a href="actualizar_mascota.php" class="w3-bar-item w3-button w3-hover-blue">Actualizar Datos de Mascota</a>
    </div>

    <!-- Cirugías -->
    <button class="w3-bar-item w3-button w3-padding w3-hover-blue" onclick="toggleSubmenu('sub-cirugias', this)">
        <i class="fa fa-medkit w3-margin-right"></i>Cirugías
        <i class="fa fa-chevron-right menu-arrow" id="arrow-sub-cirugias"></i>
    </button>
    <div id="sub-cirugias" class="submenu w3-bar-block">
        <a href="calendario_cirugias.php" class="w3-bar-item w3-button w3-hover-blue">Calendario de Cirugías</a>
        <a href="programar_cirugia.php" class="w3-bar-item w3-button w3-hover-blue">Programar Cirugía</a>
        <a href="seguimiento_postoperatorio.php" class="w3-bar-item w3-button w3-hover-blue">Seguimiento Postoperatorio</a>
    </div>

    <!-- Vacunación -->
    <button class="w3-bar-item w3-button w3-padding w3-hover-blue" onclick="toggleSubmenu('sub-vacunacion', this)">
        <i class="fa fa-flask w3-margin-right"></i>Vacunación
        <i class="fa fa-chevron-right menu-arrow" id="arrow-sub-vacunacion"></i>
    </button>
    <div id="sub-vacunacion" class="submenu w3-bar-block">
        <a href="historial_vacunacion.php" class="w3-bar-item w3-button w3-hover-blue">Historial de Vacunación</a>
        <a href="registrar_vacuna.php" class="w3-bar-item w3-button w3-hover-blue">Registrar Nueva Vacuna</a>
        <a href="proximas_vacunas.php" class="w3-bar-item w3-button w3-hover-blue">Próximas Vacunas</a>
    </div>

    <!-- Farmacia -->
    <button class="w3-bar-item w3-button w3-padding w3-hover-blue" onclick="toggleSubmenu('sub-farmacia', this)">
        <i class="fa fa-eyedropper w3-margin-right"></i>Farmacia
        <i class="fa fa-chevron-right menu-arrow" id="arrow-sub-farmacia"></i>
    </button>
    <div id="sub-farmacia" class="submenu w3-bar-block">
        <a href="inventario_farmacia.php" class="w3-bar-item w3-button w3-hover-blue">Inventario de Farmacia</a>
        <a href="entrada_medicamentos.php" class="w3-bar-item w3-button w3-hover-blue">Entrada de Medicamentos</a>
        <a href="vender_medicamento.php" class="w3-bar-item w3-button w3-hover-blue">Vender Medicamento</a>
        <a href="medicamentos_vencer.php" class="w3-bar-item w3-button w3-hover-blue">Medicamentos por Vencer</a>
    </div>

    <!-- Facturación -->
    <button class="w3-bar-item w3-button w3-padding w3-hover-blue" onclick="toggleSubmenu('sub-facturacion', this)">
        <i class="fa fa-file-text w3-margin-right"></i>Facturación
        <i class="fa fa-chevron-right menu-arrow" id="arrow-sub-facturacion"></i>
    </button>
    <div id="sub-facturacion" class="submenu w3-bar-block">
        <a href="nueva_factura.php" class="w3-bar-item w3-button w3-hover-blue">Nueva Factura</a>
        <a href="historial_facturas.php" class="w3-bar-item w3-button w3-hover-blue">Historial de Facturas</a>
        <a href="pagos_pendientes.php" class="w3-bar-item w3-button w3-hover-blue">Pagos Pendientes</a>
    </div>

    <!-- Configuración -->
    <button class="w3-bar-item w3-button w3-padding w3-hover-blue" onclick="toggleSubmenu('sub-configuracion', this)">
        <i class="fa fa-cogs w3-margin-right"></i>Configuración
        <i class="fa fa-chevron-right menu-arrow" id="arrow-sub-configuracion"></i>
    </button>
    <div id="sub-configuracion" class="submenu w3-bar-block">
        <a href="datos_clinica.php" class="w3-bar-item w3-button w3-hover-blue">Datos de la Clínica</a>
        <a href="usuarios_sistema.php" class="w3-bar-item w3-button w3-hover-blue">Usuarios del Sistema</a>
        <a href="cerrar_sesion.php" class="w3-bar-item w3-button w3-hover-blue">Cerrar Sesión</a>
    </div>

</nav>

<div class="w3-bar w3-teal w3-xlarge w3-hide-large">
    <a href="javascript:void(0)" class="w3-bar-item w3-button" onclick="abrirSidebar()">
        <i class="fa fa-bars"></i>&nbsp;&nbsp;<?php echo $nombreClinica; ?>
    </a>
</div>

<div id="main" class="w3-padding-large">