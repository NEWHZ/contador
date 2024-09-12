<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Espacio de Trabajo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container">
    <h2 class="text-center">Editar Espacio de Trabajo</h2>

    <?php if ($this->session->flashdata('error')): ?>
        <div class="alert alert-danger"><?= $this->session->flashdata('error') ?></div>
    <?php endif; ?>

    <form action="<?= base_url('espacios/update/'.$espacio['id']) ?>" method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre del Espacio</label>
            <input type="text" class="form-control" id="nombre" name="nombre" value="<?= $espacio['nombre'] ?>" required>
        </div>

        <div class="mb-3">
            <label for="descripcion" class="form-label">Descripción</label>
            <textarea class="form-control" id="descripcion" name="descripcion" rows="3"><?= $espacio['descripcion'] ?></textarea>
        </div>

        <div class="mb-3">
            <label for="estado" class="form-label">Estado del Dispositivo</label>
            <select class="form-select" id="estado" name="estado" required>
                <option value="activo" <?= $espacio['estado'] == 'activo' ? 'selected' : '' ?>>Activo</option>
                <option value="inactivo" <?= $espacio['estado'] == 'inactivo' ? 'selected' : '' ?>>Inactivo</option>
                <option value="mantenimiento" <?= $espacio['estado'] == 'mantenimiento' ? 'selected' : '' ?>>En Mantenimiento</option>
            </select>
        </div>

        <div class="mb-3">
    <label for="imagen" class="form-label">Imagen de Portada</label>
    <input type="file" class="form-control" id="imagen" name="imagen" accept="image/*">

    <!-- Mostrar la imagen si ya existe (convertida a base64 desde la base de datos) -->
    <?php if (!empty($espacio['imagen'])): ?>
        <img src="data:image/jpeg;base64,<?= base64_encode($espacio['imagen']) ?>" class="img-thumbnail mt-2" width="100">
    <?php endif; ?>
</div>


        <div class="mb-3">
            <label for="color" class="form-label">Color de Fondo</label>
            <input type="color" class="form-control" id="color" name="color" value="<?= $espacio['color_fondo'] ?>">
        </div>

        <div class="text-center">
            <button type="submit" class="btn btn-primary">Actualizar</button>
        </div>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
