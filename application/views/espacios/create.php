<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Añadir Espacio</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-4">
    <h2 class="text-center">Añadir Espacio de Trabajo</h2>

    <!-- Mostrar errores de validación si existen -->
    <?php if (validation_errors()): ?>
        <div class="alert alert-danger">
            <?= validation_errors(); ?>
        </div>
    <?php endif; ?>

    <!-- Formulario de creación -->
    <form action="<?= base_url('espacios/store') ?>" method="POST">
        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre del Espacio</label>
            <input type="text" class="form-control" id="nombre" name="nombre" required>
        </div>

        <div class="mb-3">
            <label for="descripcion" class="form-label">Descripción</label>
            <textarea class="form-control" id="descripcion" name="descripcion" rows="3"></textarea>
        </div>

        <div class="mb-3">
            <label for="estado" class="form-label">Estado del Dispositivo</label>
            <select class="form-select" id="estado" name="estado" required>
                <option value="activo">Activo</option>
                <option value="inactivo">Inactivo</option>
                <option value="mantenimiento">En Mantenimiento</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="color_fondo" class="form-label">Color de Fondo</label>
            <input type="color" class="form-control" id="color_fondo" name="color_fondo" value="#ffffff">
        </div>

        <div class="text-center">
            <button type="submit" class="btn btn-primary">Guardar</button>
        </div>
    </form>
</div>
</body>
</html>
