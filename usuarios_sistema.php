<?php

require_once 'conexion.php';
$tituloPagina = "Usuarios del Sistema";

$pdo = conectarDB();
$mensaje = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $hash = password_hash($_POST['contrasena'], PASSWORD_DEFAULT);
    $stmt = $pdo->prepare(
        "INSERT INTO usuarios (nombre_usuario, correo, contrasena_hash, rol)
         VALUES (:nombre, :correo, :hash, :rol)"
    );
    try {
        $stmt->execute([
            ':nombre' => trim($_POST['nombre_usuario']),
            ':correo' => trim($_POST['correo']),
            ':hash'   => $hash,
            ':rol'    => $_POST['rol'],
        ]);
        $mensaje = "Usuario creado correctamente.";
    } catch (PDOException $e) {
        $mensaje = "No se pudo crear el usuario (¿correo ya registrado?).";
    }
}

$usuarios = $pdo->query("SELECT id_usuario, nombre_usuario, correo, rol, fecha_creacion FROM usuarios ORDER BY id_usuario ASC")->fetchAll();

include 'includes/header.php';
?>

<div class="w3-container w3-card w3-white w3-round w3-padding-large w3-margin-bottom">
    <h2 class="w3-border-bottom w3-padding-bottom" style="color:#2c6e63;">
        <i class="fa fa-users"></i>&nbsp; Nuevo Usuario del Sistema
    </h2>

    <?php if ($mensaje): ?>
        <div class="w3-panel w3-pale-green w3-border w3-round w3-padding"><?php echo $mensaje; ?></div>
    <?php endif; ?>

    <form method="POST">
        <div class="w3-row-padding">
            <div class="w3-col m6">
                <label class="w3-text-grey"><b>Nombre de Usuario</b></label>
                <input class="w3-input w3-border w3-round" type="text" name="nombre_usuario" required>
            </div>
            <div class="w3-col m6">
                <label class="w3-text-grey"><b>Correo</b></label>
                <input class="w3-input w3-border w3-round" type="email" name="correo" required>
            </div>
        </div>
        <div class="w3-row-padding w3-margin-top">
            <div class="w3-col m6">
                <label class="w3-text-grey"><b>Contraseña</b></label>
                <input class="w3-input w3-border w3-round" type="password" name="contrasena" required>
            </div>
            <div class="w3-col m6">
                <label class="w3-text-grey"><b>Rol</b></label>
                <select class="w3-select w3-border w3-round" name="rol">
                    <option value="Administrador">Administrador</option>
                    <option value="Veterinario">Veterinario</option>
                    <option value="Recepcion">Recepción</option>
                </select>
            </div>
        </div>
        <div class="w3-margin-top">
            <button type="submit" class="w3-button w3-round w3-padding btn-vet"><i class="fa fa-save"></i>&nbsp; Crear Usuario</button>
        </div>
    </form>
</div>

<div class="w3-container w3-card w3-white w3-round w3-padding-large">
    <h3 style="color:#2c6e63;">Usuarios Registrados</h3>
    <div style="overflow-x:auto;">
    <table class="w3-table w3-striped w3-bordered w3-hoverable">
        <thead><tr class="w3-dark-grey"><th>#</th><th>Usuario</th><th>Correo</th><th>Rol</th><th>Creado</th></tr></thead>
        <tbody>
        <?php foreach ($usuarios as $u): ?>
            <tr>
                <td><?php echo $u['id_usuario']; ?></td>
                <td><b><?php echo htmlspecialchars($u['nombre_usuario']); ?></b></td>
                <td><?php echo htmlspecialchars($u['correo']); ?></td>
                <td><span class="w3-tag w3-round w3-pale-blue"><?php echo htmlspecialchars($u['rol']); ?></span></td>
                <td><?php echo date('d/m/Y', strtotime($u['fecha_creacion'])); ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
