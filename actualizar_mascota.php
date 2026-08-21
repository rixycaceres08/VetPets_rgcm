<?php

require_once 'conexion.php';
$tituloPagina = "Actualizar Datos de Mascota";

$pdo = conectarDB();
$mensaje = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_paciente'])) {
    $stmt = $pdo->prepare(
        "UPDATE pacientes
            SET nombre_mascota = :nombre_mascota,
                especie = :especie,
                raza = :raza,
                edad = :edad,
                peso = :peso,
                color_pelaje = :color_pelaje,
                fecha_ingreso = :fecha_ingreso,
                estado = :estado,
                alergias = :alergias,
                observaciones_generales = :observaciones_generales
          WHERE id_paciente = :id"
    );
    $stmt->execute([
        ':nombre_mascota'          => trim($_POST['nombre_mascota']),
        ':especie'                 => trim($_POST['especie']),
        ':raza'                    => trim($_POST['raza']),
        ':edad'                    => trim($_POST['edad']),
        ':peso'                    => $_POST['peso'] !== '' ? $_POST['peso'] : null,
        ':color_pelaje'            => trim($_POST['color_pelaje']),
        ':fecha_ingreso'           => $_POST['fecha_ingreso'] !== '' ? $_POST['fecha_ingreso'] : null,
        ':estado'                  => $_POST['estado'],
        ':alergias'                => trim($_POST['alergias']),
        ':observaciones_generales' => trim($_POST['observaciones_generales']),
        ':id'                      => (int) $_POST['id_paciente'],
    ]);
    $stmtProp = $pdo->prepare(
        "UPDATE propietarios
            SET nombre_completo = :nombre, telefono = :telefono,
                correo = :correo, direccion = :direccion
          WHERE dni = :dni"
    );
    $stmtProp->execute([
        ':nombre'    => trim($_POST['nombre_propietario']),
        ':telefono'  => trim($_POST['telefono']),
        ':correo'    => trim($_POST['correo']),
        ':direccion' => trim($_POST['direccion']),
        ':dni'       => trim($_POST['dni_propietario']),
    ]);

    $mensaje = "Los datos de la mascota y del propietario se actualizaron correctamente.";
}

$busqueda = trim($_GET['buscar'] ?? '');
$resultados = [];
if ($busqueda !== '') {
    $stmt = $pdo->prepare(
        "SELECT id_paciente, nombre_mascota, especie, dni_propietario
         FROM pacientes
         WHERE nombre_mascota LIKE :q1 OR dni_propietario LIKE :q2
         ORDER BY id_paciente ASC"
    );
    $stmt->execute([':q1' => '%' . $busqueda . '%', ':q2' => '%' . $busqueda . '%']);
    $resultados = $stmt->fetchAll();
}

$mascota = null;
if (isset($_GET['id'])) {
    $stmt = $pdo->prepare(
        "SELECT p.*, pr.nombre_completo AS nombre_propietario, pr.telefono, pr.correo, pr.direccion
         FROM pacientes p
         LEFT JOIN propietarios pr ON pr.dni = p.dni_propietario
         WHERE p.id_paciente = :id"
    );
    $stmt->execute([':id' => (int) $_GET['id']]);
    $mascota = $stmt->fetch();
}

include 'includes/header.php';
?>

<div class="w3-container w3-card w3-white w3-round w3-padding-large">
    <h2 class="w3-border-bottom w3-padding-bottom" style="color:#2c6e63;">
        <i class="fa fa-edit"></i>&nbsp; Actualizar Datos de Mascota
    </h2>

    <?php if ($mensaje): ?>
        <div class="w3-panel w3-pale-green w3-border w3-round w3-padding"><?php echo $mensaje; ?></div>
    <?php endif; ?>

    <!-- Buscador -->
    <form method="GET" class="w3-margin-bottom">
        <div class="w3-row-padding">
            <div class="w3-col m9">
                <input class="w3-input w3-border w3-round" type="text" name="buscar"
                       placeholder="Buscar por nombre de mascota o DNI del propietario..."
                       value="<?php echo htmlspecialchars($busqueda); ?>">
            </div>
            <div class="w3-col m3">
                <button type="submit" class="w3-button w3-round w3-block btn-vet"><i class="fa fa-search"></i>&nbsp; Buscar</button>
            </div>
        </div>
    </form>

    <?php if ($busqueda !== ''): ?>
        <?php if (empty($resultados)): ?>
            <p class="w3-text-grey">No se encontraron mascotas para "<?php echo htmlspecialchars($busqueda); ?>".</p>
        <?php else: ?>
            <table class="w3-table w3-striped w3-bordered w3-margin-bottom">
                <thead><tr class="w3-dark-grey"><th>Mascota</th><th>Especie</th><th>DNI Propietario</th><th></th></tr></thead>
                <tbody>
                <?php foreach ($resultados as $r): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($r['nombre_mascota']); ?></td>
                        <td><?php echo htmlspecialchars($r['especie']); ?></td>
                        <td><?php echo htmlspecialchars($r['dni_propietario']); ?></td>
                        <td>
                            <a href="?id=<?php echo $r['id_paciente']; ?>" class="w3-button w3-small w3-round btn-vet">
                                <i class="fa fa-pencil"></i>&nbsp; Editar
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    <?php endif; ?>

    <?php if ($mascota): ?>
        <h3 style="color:#2c6e63;">Editando: <?php echo htmlspecialchars($mascota['nombre_mascota']); ?></h3>
        <form method="POST">
            <input type="hidden" name="id_paciente" value="<?php echo $mascota['id_paciente']; ?>">

            <p style="font-weight:700; color:#333; border-bottom:1px solid #e2e2e2; padding-bottom:8px;">Datos de la Mascota</p>
            <div class="w3-row-padding">
                <div class="w3-col m6">
                    <label class="w3-text-grey"><b>Nombre de la Mascota</b></label>
                    <input class="w3-input w3-border w3-round" type="text" name="nombre_mascota"
                           value="<?php echo htmlspecialchars($mascota['nombre_mascota']); ?>" required>
                </div>
                <div class="w3-col m6">
                    <label class="w3-text-grey"><b>Especie</b></label>
                    <input class="w3-input w3-border w3-round" type="text" name="especie"
                           value="<?php echo htmlspecialchars($mascota['especie']); ?>" required>
                </div>
            </div>
            <div class="w3-row-padding w3-margin-top">
                <div class="w3-col m6">
                    <label class="w3-text-grey"><b>Raza</b></label>
                    <input class="w3-input w3-border w3-round" type="text" name="raza"
                           value="<?php echo htmlspecialchars($mascota['raza'] ?? ''); ?>">
                </div>
                <div class="w3-col m6">
                    <label class="w3-text-grey"><b>Edad</b></label>
                    <input class="w3-input w3-border w3-round" type="text" name="edad"
                           value="<?php echo htmlspecialchars($mascota['edad'] ?? ''); ?>">
                </div>
            </div>
            <div class="w3-row-padding w3-margin-top">
                <div class="w3-col m6">
                    <label class="w3-text-grey"><b>Peso (kg)</b></label>
                    <input class="w3-input w3-border w3-round" type="number" step="0.01" name="peso"
                           value="<?php echo htmlspecialchars($mascota['peso'] ?? ''); ?>">
                </div>
                <div class="w3-col m6">
                    <label class="w3-text-grey"><b>Color / Pelaje</b></label>
                    <input class="w3-input w3-border w3-round" type="text" name="color_pelaje"
                           value="<?php echo htmlspecialchars($mascota['color_pelaje'] ?? ''); ?>">
                </div>
            </div>
            <div class="w3-row-padding w3-margin-top">
                <div class="w3-col m6">
                    <label class="w3-text-grey"><b>Fecha de Ingreso</b></label>
                    <input class="w3-input w3-border w3-round" type="date" name="fecha_ingreso"
                           value="<?php echo htmlspecialchars($mascota['fecha_ingreso'] ?? ''); ?>">
                </div>
                <div class="w3-col m6">
                    <label class="w3-text-grey"><b>Estado</b></label>
                    <select class="w3-select w3-border w3-round" name="estado">
                        <option value="Activo"    <?php echo $mascota['estado'] === 'Activo' ? 'selected' : ''; ?>>Activo</option>
                        <option value="Inactivo"  <?php echo $mascota['estado'] === 'Inactivo' ? 'selected' : ''; ?>>Inactivo</option>
                        <option value="Cancelado" <?php echo $mascota['estado'] === 'Cancelado' ? 'selected' : ''; ?>>Cancelado</option>
                    </select>
                </div>
            </div>

            <p style="font-weight:700; color:#333; border-bottom:1px solid #e2e2e2; padding-bottom:8px; margin-top:22px;">Datos del Propietario</p>
            <div class="w3-row-padding">
                <div class="w3-col m6">
                    <label class="w3-text-grey"><b>Nombre del Propietario</b></label>
                    <input class="w3-input w3-border w3-round" type="text" name="nombre_propietario"
                           value="<?php echo htmlspecialchars($mascota['nombre_propietario'] ?? ''); ?>">
                </div>
                <div class="w3-col m6">
                    <label class="w3-text-grey"><b>Teléfono</b></label>
                    <input class="w3-input w3-border w3-round" type="text" name="telefono"
                           value="<?php echo htmlspecialchars($mascota['telefono'] ?? ''); ?>">
                </div>
            </div>
            <div class="w3-row-padding w3-margin-top">
                <div class="w3-col m6">
                    <label class="w3-text-grey"><b>Correo Electrónico</b></label>
                    <input class="w3-input w3-border w3-round" type="email" name="correo"
                           value="<?php echo htmlspecialchars($mascota['correo'] ?? ''); ?>">
                </div>
                <div class="w3-col m6">
                    <label class="w3-text-grey"><b>Dirección</b></label>
                    <input class="w3-input w3-border w3-round" type="text" name="direccion"
                           value="<?php echo htmlspecialchars($mascota['direccion'] ?? ''); ?>">
                </div>
            </div>
            <div class="w3-row-padding w3-margin-top">
                <div class="w3-col m6">
                    <label class="w3-text-grey"><b>DNI del Propietario</b></label>
                    <input class="w3-input w3-border w3-round" type="text" name="dni_propietario"
                           value="<?php echo htmlspecialchars($mascota['dni_propietario']); ?>" readonly
                           style="background-color:#f1f1f1;">
                </div>
            </div>

            <p style="font-weight:700; color:#333; border-bottom:1px solid #e2e2e2; padding-bottom:8px; margin-top:22px;">Información Médica</p>
            <div class="w3-row-padding">
                <div class="w3-col m6">
                    <label class="w3-text-grey"><b>Alergias Conocidas</b></label>
                    <textarea class="w3-input w3-border w3-round w3-textarea" name="alergias" rows="3"><?php echo htmlspecialchars($mascota['alergias'] ?? ''); ?></textarea>
                </div>
                <div class="w3-col m6">
                    <label class="w3-text-grey"><b>Observaciones Generales</b></label>
                    <textarea class="w3-input w3-border w3-round w3-textarea" name="observaciones_generales" rows="3"><?php echo htmlspecialchars($mascota['observaciones_generales'] ?? ''); ?></textarea>
                </div>
            </div>

            <div class="w3-margin-top">
                <button type="submit" class="w3-button w3-round w3-padding btn-vet"><i class="fa fa-save"></i>&nbsp; Guardar Cambios</button>
                <a href="listar_pacientes.php" class="w3-button w3-round w3-padding w3-light-grey w3-margin-left">Cancelar</a>
            </div>
        </form>
    <?php endif; ?>

</div>

<?php include 'includes/footer.php'; ?>