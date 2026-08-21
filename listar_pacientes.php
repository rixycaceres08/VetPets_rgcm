<?php

require_once 'conexion.php';
$tituloPagina = "Lista de Mascotas";

$pdo = conectarDB();

$porPagina = 5;
$paginaActual = isset($_GET['pagina']) ? (int) $_GET['pagina'] : 1;
if ($paginaActual < 1) { $paginaActual = 1; }
$offset = ($paginaActual - 1) * $porPagina;

$busqueda = trim($_GET['buscar'] ?? '');
$condicionBusqueda = "";
$parametros = [];
if ($busqueda !== '') {
    $condicionBusqueda = "WHERE p.nombre_mascota LIKE :b1 OR p.especie LIKE :b2
                            OR p.raza LIKE :b3 OR pr.nombre_completo LIKE :b4";
    $like = '%' . $busqueda . '%';
    $parametros = [':b1' => $like, ':b2' => $like, ':b3' => $like, ':b4' => $like];
}

$sqlTotal = "SELECT COUNT(*) FROM pacientes p
             LEFT JOIN propietarios pr ON pr.dni = p.dni_propietario
             $condicionBusqueda";
$stmtTotal = $pdo->prepare($sqlTotal);
$stmtTotal->execute($parametros);
$totalPacientes = (int) $stmtTotal->fetchColumn();

$totalPaginas = (int) ceil($totalPacientes / $porPagina);
if ($totalPaginas < 1) { $totalPaginas = 1; }
if ($paginaActual > $totalPaginas) {
    $paginaActual = $totalPaginas;
    $offset = ($paginaActual - 1) * $porPagina;
}

$sql = "SELECT p.id_paciente, p.nombre_mascota, p.especie, p.raza, p.edad, p.fecha_ingreso, p.estado,
               pr.nombre_completo AS nombre_propietario, pr.telefono
        FROM pacientes p
        LEFT JOIN propietarios pr ON pr.dni = p.dni_propietario
        $condicionBusqueda
        ORDER BY p.id_paciente ASC
        LIMIT :limite OFFSET :offset";
$stmt = $pdo->prepare($sql);
foreach ($parametros as $clave => $valor) {
    $stmt->bindValue($clave, $valor);
}
$stmt->bindValue(':limite', $porPagina, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$pacientes = $stmt->fetchAll();

include 'includes/header.php';
?>

<style>
    .badge-estado {
        border: none;
        border-radius: 20px;
        padding: 5px 12px;
        font-size: 12px;
        font-weight: 700;
        cursor: pointer;
        appearance: none;
        -webkit-appearance: none;
        text-align: center;
        text-align-last: center;
    }
    .badge-Activo    { background-color: #d9f5e0; color: #1e7d3c; }
    .badge-Inactivo  { background-color: #eeeeee; color: #666666; }
    .badge-Cancelado { background-color: #fbdcdc; color: #c23333; }

    .accion-circulo {
        display: inline-flex; align-items: center; justify-content: center;
        width: 32px; height: 32px; border-radius: 50%;
        margin-right: 4px; text-decoration: none;
    }
    .accion-editar  { background-color: #d9f0ec; color: #2c6e63; }
    .accion-eliminar{ background-color: #fbdcdc; color: #c23333; }

    .pag-btn {
        border-radius: 8px !important;
        border: 1px solid #d8dee1 !important;
        min-width: 42px; text-align: center;
    }
    .pag-activa { background-color: #2c6e63 !important; color:#fff !important; border-color:#2c6e63 !important; }
</style>

<div class="w3-container w3-card w3-white w3-round-large w3-padding-large">
    <div class="w3-bar w3-margin-bottom">
        <span class="w3-bar-item" style="padding-left:0;">
            <h2 style="color:#2c6e63; display:inline-block; margin:0;">
                <i class="fa fa-heart-o"></i>&nbsp; Lista de Mascotas
            </h2>
        </span>
        <a href="registro_pacientes.php" class="w3-button w3-round w3-right btn-vet">
            <i class="fa fa-plus"></i>&nbsp; Nuevo Paciente
        </a>
    </div>

    <?php if (isset($_GET['eliminado'])): ?>
        <div class="w3-panel w3-pale-green w3-border w3-round w3-padding">
            <i class="fa fa-check-circle"></i>&nbsp; Paciente eliminado correctamente.
        </div>
    <?php endif; ?>
    <?php if (isset($_GET['estado_actualizado'])): ?>
        <div class="w3-panel w3-pale-green w3-border w3-round w3-padding">
            <i class="fa fa-check-circle"></i>&nbsp; Estado actualizado correctamente.
        </div>
    <?php endif; ?>
    <form method="GET" class="w3-margin-bottom">
        <div style="position:relative;">
            <i class="fa fa-search" style="position:absolute; left:16px; top:14px; color:#999;"></i>
            <input class="w3-input w3-border w3-round-large" type="text" name="buscar"
                   placeholder="Buscar por nombre, especie, raza o propietario..."
                   value="<?php echo htmlspecialchars($busqueda); ?>"
                   style="padding-left:40px !important; padding-top:12px; padding-bottom:12px;">
        </div>
    </form>

    <?php if ($totalPacientes === 0): ?>
        <p class="w3-text-grey w3-center w3-padding-32">
            <?php echo $busqueda !== '' ? 'No se encontraron mascotas para "' . htmlspecialchars($busqueda) . '".' : 'Aún no hay pacientes registrados.'; ?>
        </p>
    <?php else: ?>

    <div style="overflow-x:auto;">
    <table class="w3-table" style="border-collapse:separate; border-spacing:0 6px;">
        <thead>
            <tr class="w3-dark-grey">
                <th style="border-radius:8px 0 0 8px;">Nombre</th>
                <th>Especie / Raza</th>
                <th>Edad</th>
                <th>Propietario</th>
                <th>Teléfono</th>
                <th>Ingreso</th>
                <th>Estado</th>
                <th style="border-radius:0 8px 8px 0;">Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($pacientes as $p): ?>
            <tr class="w3-white">
                <td><b><?php echo htmlspecialchars($p['nombre_mascota']); ?></b></td>
                <td><?php echo htmlspecialchars($p['especie']) . ($p['raza'] ? ' · ' . htmlspecialchars($p['raza']) : ''); ?></td>
                <td><?php echo htmlspecialchars($p['edad'] ?: '—'); ?></td>
                <td><?php echo htmlspecialchars($p['nombre_propietario'] ?: 'Sin especificar'); ?></td>
                <td><?php echo htmlspecialchars($p['telefono'] ?: '—'); ?></td>
                <td><?php echo $p['fecha_ingreso'] ? date('d/m/Y', strtotime($p['fecha_ingreso'])) : '—'; ?></td>
                <td>
                    <form method="POST" action="actualizar_estado_paciente.php" style="margin:0;">
                        <input type="hidden" name="id_paciente" value="<?php echo $p['id_paciente']; ?>">
                        <input type="hidden" name="pagina" value="<?php echo $paginaActual; ?>">
                        <input type="hidden" name="buscar" value="<?php echo htmlspecialchars($busqueda); ?>">
                        <select name="estado" class="badge-estado badge-<?php echo $p['estado']; ?>" onchange="this.form.submit()">
                            <option value="Activo"    <?php echo $p['estado'] === 'Activo' ? 'selected' : ''; ?>>Activo</option>
                            <option value="Inactivo"  <?php echo $p['estado'] === 'Inactivo' ? 'selected' : ''; ?>>Inactivo</option>
                            <option value="Cancelado" <?php echo $p['estado'] === 'Cancelado' ? 'selected' : ''; ?>>Cancelado</option>
                        </select>
                    </form>
                </td>
                <td style="white-space:nowrap;">
                    <a href="actualizar_mascota.php?id=<?php echo $p['id_paciente']; ?>"
                       class="accion-circulo accion-editar" title="Editar">
                        <i class="fa fa-pencil"></i>
                    </a>
                    <a href="eliminar_paciente.php?id=<?php echo $p['id_paciente']; ?>&pagina=<?php echo $paginaActual; ?>"
                       class="accion-circulo accion-eliminar" title="Eliminar"
                       onclick="return confirm('¿Seguro que deseas eliminar a <?php echo htmlspecialchars(addslashes($p['nombre_mascota'])); ?>? Esta acción no se puede deshacer.');">
                        <i class="fa fa-trash"></i>
                    </a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    </div>
    <div class="w3-bar w3-center w3-margin-top" style="flex-wrap:wrap;">
        <a href="?pagina=<?php echo max(1, $paginaActual - 1); ?>&buscar=<?php echo urlencode($busqueda); ?>"
           class="w3-button pag-btn w3-margin-right <?php echo $paginaActual <= 1 ? 'w3-disabled' : ''; ?>">
            Anterior
        </a>

        <?php for ($i = 1; $i <= $totalPaginas; $i++): ?>
            <a href="?pagina=<?php echo $i; ?>&buscar=<?php echo urlencode($busqueda); ?>"
               class="w3-button pag-btn w3-margin-right <?php echo $i === $paginaActual ? 'pag-activa' : ''; ?>">
                <?php echo $i; ?>
            </a>
        <?php endfor; ?>

        <a href="?pagina=<?php echo min($totalPaginas, $paginaActual + 1); ?>&buscar=<?php echo urlencode($busqueda); ?>"
           class="w3-button pag-btn <?php echo $paginaActual >= $totalPaginas ? 'w3-disabled' : ''; ?>">
            Siguiente
        </a>
    </div>

    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>