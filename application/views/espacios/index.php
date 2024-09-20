<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contador</title>
    <link rel="shortcut icon" href="<?php echo base_url('public/img/alarma-3d.png'); ?>" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> <!-- SweetAlert2 -->

    <style>
        /* Header Styling */
        .header-container {
            padding: 0;
            margin-bottom: 25px;
            text-align: center;
            width: 100%;
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            background-color: white;
            z-index: 1000;
        }

        .header-container h2 {
            font-size: 24px;
            margin: 0;
            padding: 10px;
        }

        .container {
            margin-top: 80px;
            max-width: 1200px;
        }

        .img-thumbnail {
            width: 60px;
            height: 60px;
            object-fit: cover;
        }

        header {
    background-color: #2c3e50; /* Fondo oscuro sobrio */
    border-bottom: 1px solid #1a252f; /* Borde inferior */
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1); /* Sombra suave */
    padding: 10px 0; /* Un poco de espacio */
}

header h2 {
    color: #ffffff; /* Texto claro para el título */
    font-size: 1.5rem; /* Tamaño del título más grande */
    font-weight: 600; /* Más peso para el título */
}

.dropdown-menu {
    background-color: #34495e; /* Fondo oscuro para el menú desplegable */
    border: none; /* Sin borde para un diseño más limpio */
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); /* Sombra suave para el dropdown */
}

.dropdown-item {
    color: #ffffff; /* Texto claro en el dropdown */
    padding: 10px 20px; /* Más espacio en cada enlace */
    transition: background-color 0.3s ease; /* Transición suave */
}

.dropdown-item:hover, 
.dropdown-item.active {
    background-color: #1abc9c; /* Color verde suave en hover o activo */
    color: #ffffff; /* Texto blanco en hover o activo */
}




        /* Button styles */
        .btn.btn-primary {
            background-color: #0f3c80;
            border-color: #0f3c80;
            color: white;
        }

        .btn.btn-primary:hover {
            background-color: #8a8a8a;
            border-color: #8a8a8a;
        }

        .btn.btn-primary:active {
            background-color: #0f3c80;
            border-color: #0f3c80;
        }

        .btn.btn-success {
            background-color: #0f3c80;
            border-color: #0f3c80;
            color: white;
        }

        .btn.btn-success:hover {
            background-color: #8a8a8a;
            border-color: #8a8a8a;
        }

        .btn.btn-success:active {
            background-color: #1e7e34;
            border-color: #1c7430;
        }

        .btn.btn-warning {
            background-color: #e6b31b;
            border-color: #e6b31b;
            color: black;
        }

        .btn.btn-warning:hover {
            background-color: #e0a800;
            border-color: #e0a800;
        }

        .btn.btn-warning:active {
            background-color: #d39e00;
            border-color: #c69500;
        }

        .btn.btn-danger {
            background-color: #dc3545;
            border-color: #dc3545;
            color: white;
        }

        .btn.btn-danger:hover {
            background-color: #c82333;
            border-color: #bd2130;
        }

        .btn.btn-danger:active {
            background-color: #b21f2d;
            border-color: #a71d2a;
        }

        .action-btn {
            margin: 0 5px;
        }

        /* Add the small color circle for background color in the table */
        .color-box {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            display: inline-block;
        }

        /* Ensuring better layout for the color selector */
        .form-select option {
            color: black;
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
        <?php if ($this->session->flashdata('success')) : ?>
            <div class="alert alert-success"><?= $this->session->flashdata('success') ?></div>
        <?php endif; ?>
        <?php if ($this->session->flashdata('error')) : ?>
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
                <?php foreach ($espacios as $espacio) : ?>
                    <tr>
                        <td><?= $espacio['id'] ?></td>
                        <td><?= $espacio['nombre'] ?></td>
                        <td><?= $espacio['descripcion'] ?></td>
                        <td><?= $espacio['estado'] ?></td>
                        <td>
                            <?php if (!empty($espacio['imagen'])) : ?>
                                <img src="data:image/jpeg;base64,<?= base64_encode($espacio['imagen']) ?>" class="img-thumbnail">
                            <?php else : ?>
                                Sin imagen
                            <?php endif; ?>
                        </td>
                        <!-- Color circle in the table cell -->
                        <td>
                            <div class="color-box" style="background-color: <?= $espacio['color_fondo'] ?>;"></div>
                        </td>
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
                            <div id="imagenPreview"></div>
                        </div>
                        <div class="mb-3">
    <label for="color" class="form-label">Color de Fondo</label>
    <div id="color-options" class="d-flex justify-content-between">
        <div class="color-circle" data-color="#f79256" style="background-color: #f79256;"></div>
        <div class="color-circle" data-color="#fbd1a2" style="background-color: #fbd1a2;"></div>
        <div class="color-circle" data-color="#7dcfb6" style="background-color: #7dcfb6;"></div>
        <div class="color-circle" data-color="#00b2ca" style="background-color: #00b2ca;"></div>
        <div class="color-circle" data-color="#1d4e89" style="background-color: #1d4e89;"></div>
    </div>
    <input type="hidden" id="color" name="color" value="#f79256">
</div>

<style>
    .color-circle {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        cursor: pointer;
        border: 0px solid transparent;
        transition: border-color 0.3s ease;
    }

    .color-circle.selected {
        border-color: #000;
    }
</style>

<script>
    document.querySelectorAll('.color-circle').forEach(circle => {
        circle.addEventListener('click', function() {
            document.querySelectorAll('.color-circle').forEach(c => c.classList.remove('selected'));
            this.classList.add('selected');
            document.getElementById('color').value = this.getAttribute('data-color');
        });
    });
</script>


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
