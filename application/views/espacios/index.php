<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tablero de Gestión de Espacios de Trabajo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .container { margin-top: 50px; }
        .color-box { width: 30px; height: 30px; border-radius: 50%; }
        .action-btn { margin: 0 5px; }
        .img-thumbnail { width: 60px; height: 60px; object-fit: cover; }
    </style>
</head>
<body>
<div class="container">
    <h2 class="text-center">Tablero de Gestión de Espacios de Trabajo</h2>

    <!-- Mostrar mensajes de éxito o error -->
    <?php if ($this->session->flashdata('success')): ?>
        <div class="alert alert-success"><?= $this->session->flashdata('success') ?></div>
    <?php endif; ?>
    <?php if ($this->session->flashdata('error')): ?>
        <div class="alert alert-danger"><?= $this->session->flashdata('error') ?></div>
    <?php endif; ?>

    <!-- Botón para activar el modal de añadir espacio -->
    <button class="btn btn-success mb-3" onclick="openAddModal()">Añadir Espacio de Trabajo</button>

    <!-- Tabla de espacios -->
    <table class="table table-striped table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>Nombre</th>
                <th>Descripción</th>
                <th>Estado</th>
                <th>Imagen</th>
                <th>Color de Fondo</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($espacios as $espacio): ?>
                <tr>
                    <td><?= $espacio['id'] ?></td>
                    <td><?= $espacio['nombre'] ?></td>
                    <td><?= $espacio['descripcion'] ?></td>
                    <td><?= $espacio['estado'] ?></td>
                    <td>
                        <?php if (!empty($espacio['imagen'])): ?>
                            <img src="data:image/jpeg;base64,<?= base64_encode($espacio['imagen']) ?>" class="img-thumbnail" width="100">
                        <?php else: ?>
                            Sin imagen
                        <?php endif; ?>
                    </td>
                    <td><div class="color-box" style="background-color: <?= $espacio['color_fondo'] ?>;"></div></td>
                    <td>
                        <!-- Botón para abrir el modal de edición -->
                        <button class="btn btn-warning action-btn" onclick="editEspacio(<?= $espacio['id'] ?>)">Editar</button>

                        <!-- Botón para eliminar el espacio -->
                        <a href="<?= base_url('espacios/delete/' . $espacio['id']) ?>" class="btn btn-danger action-btn" onclick="return confirm('¿Seguro que deseas eliminar este espacio?');">Eliminar</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<!-- Modal para agregar/editar espacios de trabajo -->
<div class="modal fade" id="workspaceModal" tabindex="-1" aria-labelledby="workspaceModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="workspaceModalLabel">Añadir Espacio de Trabajo</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="workspaceForm" method="POST" enctype="multipart/form-data">
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
                        <label for="imagen" class="form-label">Imagen de Portada</label>
                        <input type="file" class="form-control" id="imagen" name="imagen" accept="image/*">
                        <div id="imagenPreview"></div> <!-- Previsualización de la imagen -->
                    </div>
                    <div class="mb-3">
                        <label for="color" class="form-label">Color de Fondo</label>
                        <input type="color" class="form-control" id="color" name="color" value="#ffffff">
                    </div>
                    <input type="hidden" id="workspaceId" name="workspaceId">
                    <div class="text-center">
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- Script para manejar el modal de edición y creación -->
<script>
function openAddModal() {
    // Cambiar el título del modal a "Añadir"
    document.getElementById("workspaceModalLabel").innerText = "Añadir Espacio de Trabajo";
    
    // Restablecer el formulario y preparar para insertar
    document.getElementById("workspaceForm").reset();
    document.getElementById("imagenPreview").innerHTML = ''; // Limpiar previsualización de imagen
    document.getElementById("workspaceForm").action = '<?= base_url('espacios/store') ?>'; // Acción para guardar

    // Abrir el modal
    var myModal = new bootstrap.Modal(document.getElementById('workspaceModal'), {
        keyboard: false
    });
    myModal.show();
}

function editEspacio(id) {
    // Cambiar el título del modal a "Editar"
    document.getElementById("workspaceModalLabel").innerText = "Editar Espacio de Trabajo";
    
    // Obtener los datos del espacio mediante AJAX
    fetch('<?= base_url('espacios/edit/') ?>' + id)
        .then(response => response.json())
        .then(data => {
            // Rellenar el formulario con los datos del espacio
            document.getElementById("workspaceId").value = data.id;
            document.getElementById("nombre").value = data.nombre;
            document.getElementById("descripcion").value = data.descripcion;
            document.getElementById("estado").value = data.estado;
            document.getElementById("color").value = data.color_fondo;

            // Previsualizar la imagen si existe
            if (data.imagen) {
                document.getElementById("imagenPreview").innerHTML = `<img src="data:image/jpeg;base64,${data.imagen}" class="img-thumbnail mt-2" width="100">`;
            } else {
                document.getElementById("imagenPreview").innerHTML = 'Sin imagen';
            }

            // Cambiar la acción del formulario para actualizar
            document.getElementById("workspaceForm").action = '<?= base_url('espacios/update/') ?>' + id;

            // Abrir el modal
            var myModal = new bootstrap.Modal(document.getElementById('workspaceModal'), {
                keyboard: false
            });
            myModal.show();
        });
}

// Restablecer el formulario y título cuando se cierra el modal
document.getElementById('workspaceModal').addEventListener('hidden.bs.modal', function () {
    document.getElementById("workspaceForm").reset();
    document.getElementById("workspaceModalLabel").innerText = "Añadir Espacio de Trabajo";
    document.getElementById("imagenPreview").innerHTML = ''; // Limpiar previsualización de la imagen
    document.getElementById("workspaceForm").action = '<?= base_url('espacios/store') ?>'; // Cambiar la acción del formulario para agregar
});
</script>
</body>
</html>
