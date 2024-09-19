<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tablero de Gestión de Espacios de Trabajo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Updated Header Styling */
        .header-container {
            padding: 0; /* Remove default padding */
            margin-bottom: 25px; /* Add space below the header */
            text-align: center; /* Center align header content */
            width: 100%; /* Ensure the header takes the full width */
            position: absolute; /* Absolute positioning */
            top: 0; /* Stick to the top of the page */
            left: 0; /* Align it to the left of the screen */
            right: 0; /* Ensure it stretches to the right */
            background-color: white; /* Optional: Add background color */
            z-index: 1000; /* Ensure it stays on top */
        }

        .header-container h2 {
            font-size: 24px;
            margin: 0; /* Ensure no extra margin on the header title */
            padding: 10px; /* Add some padding for better spacing */
        }

        .container {
            margin-top: 80px; /* Add top margin to avoid overlap with fixed header */
            max-width: 1200px; /* Control max-width to avoid stretching too much on large screens */
        }

        .color-box {
            width: 30px;
            height: 30px;
            border-radius: 50%;
        }

        .action-btn {
            margin: 0 5px;
        }

        .img-thumbnail {
            width: 60px;
            height: 60px;
            object-fit: cover;
        }
        header {
        background-color: #333; /* Fondo oscuro */
        border-bottom: 1px solid #444; /* Borde inferior */
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); /* Sombra del header */
    }

    header .nav-link {
        color: #fff; /* Color del texto */
        padding: 8px 12px;
        transition: background-color 0.3s, color 0.3s; /* Transición suave */
    }

    header .nav-link:hover {
        background-color: #555; /* Color de fondo al hacer hover */
    }

    header .nav-link.active {
        background-color: #28a745; /* Fondo verde para el enlace activo */
        color: #fff;
        border-radius: 4px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); /* Sombra para el enlace activo */
    }

    header h2 {
        color: #fff; /* Color del título */
    }
    </style>
</head>
<body>
<div class="container">
    <!-- Include the header -->
    <div class="header-container">
        <?php $this->load->view('header'); ?> 
    </div>
    
    <!-- Back to Contador Button -->
    <h2 class="text-center">Tablero de Gestión de Espacios de Trabajo</h2>

    <!-- Display success or error messages -->
    <?php if ($this->session->flashdata('success')): ?>
        <div class="alert alert-success"><?= $this->session->flashdata('success') ?></div>
    <?php endif; ?>
    <?php if ($this->session->flashdata('error')): ?>
        <div class="alert alert-danger"><?= $this->session->flashdata('error') ?></div>
    <?php endif; ?>

    <!-- Button to trigger the modal for adding workspace -->
    <button class="btn btn-success mb-3" onclick="openAddModal()">Añadir Espacio de Trabajo</button>

    <!-- Spaces table -->
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
                            <img src="data:image/jpeg;base64,<?= base64_encode($espacio['imagen']) ?>" class="img-thumbnail">
                        <?php else: ?>
                            Sin imagen
                        <?php endif; ?>
                    </td>
                    <td><div class="color-box" style="background-color: <?= $espacio['color_fondo'] ?>;"></div></td>
                    <td>
                        <!-- Edit space button -->
                        <button class="btn btn-warning action-btn" onclick="editEspacio(<?= $espacio['id'] ?>)">Editar</button>

                        <!-- Delete space button -->
                        <a href="<?= base_url('espacios/delete/' . $espacio['id']) ?>" class="btn btn-danger action-btn" onclick="return confirm('¿Seguro que deseas eliminar este espacio?');">Eliminar</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<!-- Modal for adding/editing workspaces -->
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
                        <div id="imagenPreview"></div> <!-- Image preview -->
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
<script>
    // Define a global variable for the base URL
    const BASE_URL = '<?= base_url() ?>';
</script>
<script src="<?php echo base_url('assets/js/espacios.js'); ?>"></script>
</body>
</html>
