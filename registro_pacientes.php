<?php

require_once 'conexion.php';
$tituloPagina = "Registro de Paciente";
include 'includes/header.php';
?>

<style>
    .seccion-form {
        font-size: 15px;
        font-weight: 700;
        color: #333;
        margin: 22px 0 6px;
        padding-bottom: 8px;
        border-bottom: 1px solid #e2e2e2;
    }
    .seccion-form:first-of-type { margin-top: 0; }
    .campo-requerido { color: #d64545; }
    .form-vet input[type=text],
    .form-vet input[type=number],
    .form-vet input[type=date],
    .form-vet input[type=tel],
    .form-vet input[type=email],
    .form-vet select,
    .form-vet textarea {
        border-radius: 8px !important;
        border: 1px solid #d8dee1 !important;
        padding: 10px 12px !important;
    }
    .form-vet label {
        font-size: 13px;
        font-weight: 600;
        color: #444;
        margin-bottom: 4px;
        display: inline-block;
    }
</style>

<div id="registro-pacientes" class="w3-container w3-card w3-white w3-round-large w3-padding-large form-vet">
    <div class="w3-bar">
        <span class="w3-bar-item" style="padding-left:0;">
            <h2 style="color:#2c6e63; display:inline-block; margin:0;">
                <i class="fa fa-plus"></i>&nbsp; Registro de Paciente
            </h2>
        </span>
        <a href="listar_pacientes.php" class="w3-button w3-round w3-right w3-border">
            <i class="fa fa-list-ul"></i>&nbsp; Ver Lista
        </a>
    </div>

    <?php if (isset($_GET['registrado'])): ?>
        <div class="w3-panel w3-pale-green w3-border w3-round w3-padding w3-margin-top">
            <i class="fa fa-check-circle"></i>&nbsp; Paciente registrado correctamente.
        </div>
    <?php endif; ?>

    <form action="guardar_paciente.php" method="POST">
        <p class="seccion-form">Datos de la Mascota</p>
        <div class="w3-row-padding">
            <div class="w3-col m6">
                <label>Nombre de la mascota <span class="campo-requerido">*</span></label>
                <input class="w3-input" type="text" name="nombre_mascota" placeholder="Ej. Max" required>
            </div>
            <div class="w3-col m6">
                <label>Especie</label>
                <select class="w3-select" name="especie" required>
                    <option value="Perro">Perro</option>
                    <option value="Gato">Gato</option>
                    <option value="Ave">Ave</option>
                    <option value="Conejo">Conejo</option>
                    <option value="Reptil">Reptil</option>
                    <option value="Otro">Otro</option>
                </select>
            </div>
        </div>

        <div class="w3-row-padding w3-margin-top">
            <div class="w3-col m6">
                <label>Raza</label>
                <input class="w3-input" type="text" name="raza" placeholder="Ej. Labrador Retriever">
            </div>
            <div class="w3-col m6">
                <label>Edad</label>
                <input class="w3-input" type="text" name="edad" placeholder="Ej. 2 años 3 meses">
            </div>
        </div>

        <div class="w3-row-padding w3-margin-top">
            <div class="w3-col m6">
                <label>Peso (kg)</label>
                <input class="w3-input" type="number" step="0.01" name="peso" placeholder="Ej. 15.5">
            </div>
            <div class="w3-col m6">
                <label>Color / Pelaje</label>
                <input class="w3-input" type="text" name="color_pelaje" placeholder="Ej. Dorado con blanco">
            </div>
        </div>

        <div class="w3-row-padding w3-margin-top">
            <div class="w3-col m6">
                <label>Fecha de ingreso</label>
                <input class="w3-input" type="date" name="fecha_ingreso" value="<?php echo date('Y-m-d'); ?>">
            </div>
        </div>
        <p class="seccion-form">Datos del Propietario</p>
        <div class="w3-row-padding">
            <div class="w3-col m6">
                <label>Nombre del propietario <span class="campo-requerido">*</span></label>
                <input class="w3-input" type="text" name="nombre_propietario" placeholder="Nombre completo" required>
            </div>
            <div class="w3-col m6">
                <label>Teléfono <span class="campo-requerido">*</span></label>
                <input class="w3-input" type="tel" name="telefono" placeholder="+504 8888-1234" required>
            </div>
        </div>

        <div class="w3-row-padding w3-margin-top">
            <div class="w3-col m6">
                <label>Correo electrónico</label>
                <input class="w3-input" type="email" name="correo" placeholder="correo@ejemplo.com">
            </div>
            <div class="w3-col m6">
                <label>Dirección</label>
                <input class="w3-input" type="text" name="direccion" placeholder="Ciudad, Barrio, Calle">
            </div>
        </div>

        <div class="w3-row-padding w3-margin-top">
            <div class="w3-col m6">
                <label>DNI del propietario <span class="campo-requerido">*</span></label>
                <input class="w3-input" type="text" name="dni_propietario" placeholder="Documento de identidad" required>
            </div>
        </div>
        <p class="seccion-form">Información Médica</p>
        <div class="w3-row-padding">
            <div class="w3-col m6">
                <label>Alergias conocidas</label>
                <textarea class="w3-input" name="alergias" rows="3" placeholder="Ej. Alergia a penicilina..."></textarea>
            </div>
            <div class="w3-col m6">
                <label>Observaciones generales</label>
                <textarea class="w3-input" name="observaciones_generales" rows="3" placeholder="Condición actual, comportamiento..."></textarea>
            </div>
        </div>
        <div class="w3-margin-top" style="padding-top:8px;">
            <button type="submit" class="w3-button w3-round w3-padding btn-vet">
                <i class="fa fa-check"></i>&nbsp; Guardar Paciente
            </button>
            <button type="reset" class="w3-button w3-round w3-padding w3-light-grey w3-margin-left">
                Limpiar
            </button>
        </div>
    </form>
</div>

<?php include 'includes/footer.php'; ?>