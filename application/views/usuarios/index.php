<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Usuarios</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> <!-- SweetAlert2 -->

    <style>
        .hidden {
            display: none;
        }
    </style>
</head>
<body>
<?php $this->load->view('header'); ?>  <!-- Incluir el header -->

<div class="container mt-4">
    <h2 class="text-center">Gestión de Usuarios</h2>

    <!-- Submenú para alternar entre todos los usuarios y los pendientes -->
    <ul class="nav nav-tabs">
        <li class="nav-item">
            <a class="nav-link active" href="#" onclick="toggleView('allUsers')">Todos los Usuarios</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#" onclick="toggleView('pendingUsers')">Usuarios Pendientes</a>
        </li>
    </ul>

    <!-- Tabla de todos los usuarios -->
    <div id="allUsers" class="mt-3">
        <h4>Lista de Todos los Usuarios</h4>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre de usuario</th>
                    <th>Email</th>
                    <th>Rol</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($usuarios as $usuario): ?>
                    <tr>
                        <td><?= $usuario['id'] ?></td>
                        <td><?= $usuario['username'] ?></td>
                        <td><?= $usuario['email'] ?></td>
                        <td><?= $usuario['role_id'] == 1 ? 'Admin' : 'Usuario' ?></td>
                        <td><?= $usuario['status'] == 1 ? 'Activo' : 'Pendiente' ?></td>
                        <td>
                            <button class="btn btn-warning btn-sm" onclick="openEditModal(<?= $usuario['id'] ?>, '<?= $usuario['username'] ?>', '<?= $usuario['email'] ?>', <?= $usuario['role_id'] ?>)">Editar</button>
                            <a href="<?= site_url('usuarios/delete/' . $usuario['id']) ?>" class="btn btn-danger btn-sm" onclick="return confirmDelete('<?= $usuario['id'] ?>')">Eliminar</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Tabla de usuarios pendientes -->
    <div id="pendingUsers" class="hidden mt-3">
        <h4>Lista de Usuarios Pendientes</h4>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre de usuario</th>
                    <th>Email</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($usuarios as $usuario): ?>
                    <?php if ($usuario['status'] == 0): ?>
                        <tr>
                            <td><?= $usuario['id'] ?></td>
                            <td><?= $usuario['username'] ?></td>
                            <td><?= $usuario['email'] ?></td>
                            <td>
                                <a href="<?= site_url('usuarios/aprobar/' . $usuario['id']) ?>" class="btn btn-success btn-sm">Aprobar</a>
                                <a href="<?= site_url('usuarios/delete/' . $usuario['id']) ?>" class="btn btn-danger btn-sm" onclick="return confirmDelete('<?= $usuario['id'] ?>')">Eliminar</a>
                            </td>
                        </tr>
                    <?php endif; ?>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal para editar usuario -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Editar Usuario</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editUserForm" method="POST" action="<?= site_url('usuarios/update') ?>">
                    <input type="hidden" name="id" id="editUserId">
                    <div class="mb-3">
                        <label for="editUsername" class="form-label">Nombre de usuario</label>
                        <input type="text" class="form-control" name="username" id="editUsername" required>
                    </div>
                    <div class="mb-3">
                        <label for="editEmail" class="form-label">Correo electrónico</label>
                        <input type="email" class="form-control" name="email" id="editEmail" required>
                    </div>
                    <div class="mb-3">
                        <label for="editRole" class="form-label">Rol</label>
                        <select class="form-select" name="role_id" id="editRole">
                            <option value="1">Admin</option>
                            <option value="2">Usuario</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Guardar cambios</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    // Confirmación de eliminación con SweetAlert2
    function confirmDelete(id) {
        Swal.fire({
            title: '¿Estás seguro?',
            text: "No podrás revertir esta acción",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, eliminarlo'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = '<?= site_url('usuarios/delete/') ?>' + id;
            }
        });
    }

    // Abrir modal para editar usuario
    function openEditModal(id, username, email, role_id) {
        document.getElementById('editUserId').value = id;
        document.getElementById('editUsername').value = username;
        document.getElementById('editEmail').value = email;
        document.getElementById('editRole').value = role_id;

        // Inicializar y mostrar el modal de edición
        var editModal = new bootstrap.Modal(document.getElementById('editModal'));
        editModal.show();
    }

    // Función para alternar entre vistas
    function toggleView(view) {
        document.getElementById('allUsers').classList.add('hidden');
        document.getElementById('pendingUsers').classList.add('hidden');
        
        if (view === 'allUsers') {
            document.getElementById('allUsers').classList.remove('hidden');
        } else if (view === 'pendingUsers') {
            document.getElementById('pendingUsers').classList.remove('hidden');
        }

        // Cambiar la pestaña activa
        document.querySelector('.nav-link.active').classList.remove('active');
        if (view === 'allUsers') {
            document.querySelector('.nav-link[href="#allUsers"]').classList.add('active');
        } else if (view === 'pendingUsers') {
            document.querySelector('.nav-link[href="#pendingUsers"]').classList.add('active');
        }
    }
</script>

<!-- Cargar los scripts de Bootstrap y dependencias al final -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
