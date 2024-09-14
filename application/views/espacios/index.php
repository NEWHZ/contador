<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tablero de Gestión de Espacios de Trabajo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> <!-- Incluye SweetAlert2 -->
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
                        <button class="btn btn-warning action-btn" onclick="editEspacio(<?= $espacio['id'] ?>)">Editar</button>
                        <button class="btn btn-danger action-btn" onclick="confirmDelete(<?= $espacio['id'] ?>)">Eliminar</button>
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
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
// Muestra la alerta solo si existe un mensaje flashdata
<?php if ($this->session->flashdata('success')): ?>
    Swal.fire({
        icon: 'success',
        title: '¡Éxito!',
        text: '<?= $this->session->flashdata('success') ?>',
        timer: 3000,
        showConfirmButton: false
    }).then(() => {
        // Limpiar el flashdata después de mostrar la alerta
        $.ajax({
            url: '<?= base_url("espacios/clearFlashdata") ?>',
            method: 'GET'
        });
    });
<?php endif; ?>

<?php if ($this->session->flashdata('error')): ?>
    Swal.fire({
        icon: 'error',
        title: '¡Error!',
        text: '<?= $this->session->flashdata('error') ?>',
        timer: 3000,
        showConfirmButton: false
    }).then(() => {
        // Limpiar el flashdata después de mostrar la alerta
        $.ajax({
            url: '<?= base_url("espacios/clearFlashdata") ?>',
            method: 'GET'
        });
    });
<?php endif; ?>

// Función para confirmar el borrado lógico
function confirmDelete(id) {
    Swal.fire({
        title: '¿Estás seguro?',
        text: "¡No podrás revertir esto!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, eliminarlo'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = '<?= base_url("espacios/delete/") ?>' + id;
        }
    });
}

// Función para abrir el modal de agregar
function openAddModal() {
    document.getElementById("workspaceModalLabel").innerText = "Añadir Espacio de Trabajo";
    document.getElementById("workspaceForm").reset();
    document.getElementById("imagenPreview").innerHTML = ''; // Limpiar previsualización de imagen
    document.getElementById("workspaceForm").action = '<?= base_url('espacios/store') ?>';
    var myModal = new bootstrap.Modal(document.getElementById('workspaceModal'), {
        keyboard: false
    });
    myModal.show();
}

// Función para abrir el modal de editar
function editEspacio(id) {
    document.getElementById("workspaceModalLabel").innerText = "Editar Espacio de Trabajo";
    fetch('<?= base_url('espacios/edit/') ?>' + id)
        .then(response => response.json())
        .then(data => {
            document.getElementById("workspaceId").value = data.id;
            document.getElementById("nombre").value = data.nombre;
            document.getElementById("descripcion").value = data.descripcion;
            document.getElementById("estado").value = data.estado;
            document.getElementById("color").value = data.color_fondo;

            if (data.imagen) {
                document.getElementById("imagenPreview").innerHTML = `<img src="data:image/jpeg;base64,${data.imagen}" class="img-thumbnail mt-2" width="100">`;
            } else {
                document.getElementById("imagenPreview").innerHTML = 'Sin imagen';
            }

            document.getElementById("workspaceForm").action = '<?= base_url('espacios/update/') ?>' + id;
            var myModal = new bootstrap.Modal(document.getElementById('workspaceModal'), {
                keyboard: false
            });
            myModal.show();
        });
}
</script>
</body>
</html>
