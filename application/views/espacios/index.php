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
<<<<<<< HEAD
        .container { margin-top: 50px; }
        .color-box { width: 30px; height: 30px; border-radius: 50%; }
        .img-thumbnail { width: 60px; height: 60px; object-fit: cover; }

        /* Estilos para pantallas pequeñas */
        @media (max-width: 768px) {
            /* Ocultar columnas de descripción, imagen y color en pantallas pequeñas */
            .table td:nth-child(3), .table td:nth-child(5), .table td:nth-child(6),
            .table th:nth-child(3), .table th:nth-child(5), .table th:nth-child(6) {
                display: none;
            }

            /* Mostrar el icono de "Ver" solo en pantallas pequeñas */
            .table td i.fa-eye {
                display: inline-block;
            }
        }

        /* Ocultar el icono "Ver" en pantallas grandes */
        @media (min-width: 769px) {
            .table td i.fa-eye {
                display: none;
            }
=======
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
>>>>>>> vista-contador
        }
    </style>
</head>

<body>
<<<<<<< HEAD
<div class="container">
    <a href="<?= base_url('') ?>" class="btn btn-primary mb-3">Volver al Contador</a>
    <a href="<?= base_url('index.php/categorias') ?>" class="btn btn-primary mb-3">Agregar Categoría</a>
    <h2 class="text-center">Tablero de Gestión de Espacios de Trabajo</h2>

    <!-- Mostrar mensajes con SweetAlert2 -->
    <script>
        <?php if ($this->session->flashdata('success')): ?>
            Swal.fire({
                icon: 'success',
                title: 'Éxito',
                text: '<?= $this->session->flashdata('success') ?>',
                timer: 3000,
                showConfirmButton: false
            });
        <?php endif; ?>

        <?php if ($this->session->flashdata('error')): ?>
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: '<?= $this->session->flashdata('error') ?>',
                timer: 3000,
                showConfirmButton: false
            });
        <?php endif; ?>
    </script>
=======
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
>>>>>>> vista-contador

        <!-- Button to trigger the modal for adding workspace -->
        <button class="btn btn-success mb-3" onclick="openAddModal()">Añadir Espacio de Trabajo</button>

<<<<<<< HEAD
    <!-- Tabla de espacios, con columnas ocultas en pantallas pequeñas -->
    <div class="table-responsive">
=======
        <!-- Spaces table -->
>>>>>>> vista-contador
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th>Estado</th>
                    <th>Imagen</th>
                    <th>Color de Fondo</th>
<<<<<<< HEAD
                    <th>Categoría</th>
=======
>>>>>>> vista-contador
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
<<<<<<< HEAD
                <?php foreach ($espacios as $espacio): ?>
=======
                <?php foreach ($espacios as $espacio) : ?>
>>>>>>> vista-contador
                    <tr>
                        <td><?= $espacio['id'] ?></td>
                        <td><?= $espacio['nombre'] ?></td>
                        <td><?= $espacio['descripcion'] ?></td>
                        <td><?= $espacio['estado'] ?></td>
                        <td>
<<<<<<< HEAD
                            <?php if (!empty($espacio['imagen'])): ?>
                                <img src="data:image/jpeg;base64,<?= base64_encode($espacio['imagen']) ?>" class="img-thumbnail">
                            <?php else: ?>
                                Sin imagen
                            <?php endif; ?>
                        </td>
                        <td><div class="color-box" style="background-color: <?= $espacio['color_fondo'] ?>;"></div></td>
                        <td><?= $espacio['nombre_categoria'] ?></td>
                        <td>
                            <!-- Iconos para ver detalles, editar y eliminar -->
                            <i class="fas fa-eye text-info" title="Ver" onclick="viewDetails(<?= $espacio['id'] ?>)"></i>
                            <i class="fas fa-edit text-warning" title="Editar" onclick="editEspacio(<?= $espacio['id'] ?>)"></i>
                            <i class="fas fa-trash text-danger" title="Eliminar" onclick="confirmDelete(<?= $espacio['id'] ?>)"></i>
=======
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
>>>>>>> vista-contador
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<<<<<<< HEAD
</div>

<!-- Modal para ver detalles del espacio de trabajo -->
<div class="modal fade" id="viewDetailsModal" tabindex="-1" aria-labelledby="viewDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewDetailsModalLabel">Detalles del Espacio de Trabajo</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="modalDetailsBody">
                <!-- Contenido dinámico del modal, se llenará con JavaScript -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal para agregar/editar espacio de trabajo -->
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
                        <label for="categoria_id" class="form-label">Categoría</label>
                        <select class="form-select" id="categoria_id" name="categoria_id" required>
                            <option value="">Seleccionar Categoría</option>
                            <?php foreach ($categorias as $categoria): ?>
                                <option value="<?= $categoria['id'] ?>"><?= $categoria['nombre'] ?></option>
                            <?php endforeach; ?>
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
=======

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
>>>>>>> vista-contador
            </div>
        </div>
    </div>

<<<<<<< HEAD
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    const BASE_URL = '<?= base_url() ?>';

    // Función para abrir el modal con los detalles de un espacio de trabajo
    function viewDetails(id) {
        // Verificar que la URL para obtener los detalles sea correcta
        const url = BASE_URL + 'espacios/getDetails/' + id;

        // Hacer una petición AJAX para obtener los detalles del espacio
        fetch(url)
            .then(response => {
                if (!response.ok) {
                    throw new Error(`Error: ${response.statusText}`);
                }
                return response.json();
            })
            .then(data => {
                let modalBody = `
                    <p><strong>Nombre:</strong> ${data.nombre}</p>
                    <p><strong>Estado:</strong> ${data.estado}</p>
                    <p><strong>Categoría:</strong> ${data.nombre_categoria}</p>
                    <p><strong>Color de Fondo:</strong> <div class="color-box" style="background-color: ${data.color_fondo};"></div></p>
                    ${data.imagen ? '<img src="data:image/jpeg;base64,' + data.imagen + '" class="img-fluid">' : '<p>Sin imagen</p>'}
                `;
                document.getElementById('modalDetailsBody').innerHTML = modalBody;

                // Mostrar el modal una vez que los datos se hayan cargado
                var viewDetailsModal = new bootstrap.Modal(document.getElementById('viewDetailsModal'), {
                    keyboard: false
                });
                viewDetailsModal.show();
            })
            .catch(error => {
                console.error('Error al obtener los detalles:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: `Hubo un problema al obtener los detalles del espacio: ${error.message}`
                });
            });
    }

    // Función para editar espacio (llenar el modal de edición)
    function editEspacio(id) {
        const url = BASE_URL + 'espacios/getDetails/' + id;
        // Hacer una petición AJAX para obtener los detalles del espacio a editar
        fetch(url)
            .then(response => response.json())
            .then(data => {
                document.getElementById('nombre').value = data.nombre;
                document.getElementById('descripcion').value = data.descripcion;
                document.getElementById('estado').value = data.estado;
                document.getElementById('categoria_id').value = data.categoria_id;
                document.getElementById('color').value = data.color_fondo;
                document.getElementById('workspaceId').value = data.id;
                var editModal = new bootstrap.Modal(document.getElementById('workspaceModal'));
                editModal.show();
            });
    }

    // Confirmación de eliminación con SweetAlert2
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
                window.location.href = BASE_URL + 'espacios/delete/' + id;
            }
        });
    }
</script>
<script src="<?= base_url('assets/js/espacios.js'); ?>"></script> <!-- Enlace al JS externo -->
=======
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Define a global variable for the base URL
        const BASE_URL = '<?= base_url() ?>';
    </script>
    <script src="<?php echo base_url('assets/js/espacios.js'); ?>"></script>
>>>>>>> vista-contador
</body>

</html>
