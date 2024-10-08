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
        /* Header styling */
        header {
            background-color: #333;
            border-bottom: 1px solid #444;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        header .nav-link {
            color: #fff;
            padding: 8px 12px;
            transition: background-color 0.3s, color 0.3s;
        }
        header .nav-link:hover {
            background-color: #555;
        }
        header .nav-link.active {
            background-color: #28a745;
            color: #fff;
            border-radius: 4px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
        header h2 {
            color: #fff;
        }

        /* Table styles */
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
        .color-circle {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: inline-block;
    cursor: pointer;
    margin: 5px;
    border: 2px solid transparent;
    transition: border 0.3s ease;
}

.color-circle.selected {
    border: 2px solid black; /* Cambiar el borde para indicar la selección */
}


        /* Ocultar el icono "Ver" en pantallas grandes */
        @media (min-width: 769px) {
            .table td i.fa-eye {
                display: none;
            }
        }
    </style>
</head>
<body>

<!-- Header -->
<?php
    // Obtener la URI actual para determinar qué enlace está activo
    $uri_segments = explode('/', parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
    $current_page = end($uri_segments);
?>

<header class="navbar navbar-expand-md navbar-dark bg-dark px-3 py-2">
    <a class="navbar-brand" href="#">Device Time</a>
    <!-- Botón hamburguesa para pantallas pequeñas -->
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMenu" aria-controls="navbarMenu" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarMenu">
        <ul class="navbar-nav ms-auto">
            <li class="nav-item">
                <a class="nav-link <?php echo $current_page == 'alquiler' ? 'active' : ''; ?>" href="/contador/index.php/alquiler">Historial</a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo $current_page == 'espacios' ? 'active' : ''; ?>" href="/contador/index.php/espacios">Dispositivos</a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo $current_page == 'tablero' ? 'active' : ''; ?>" href="<?php echo site_url('tablero'); ?>">Show Time</a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo $current_page == 'asignarTiempo' ? 'active' : ''; ?>" href="<?php echo site_url('asignarTiempo'); ?>">Asignar Tiempo</a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo $current_page == 'categorias' ? 'active' : ''; ?>" href="<?php echo site_url('categorias'); ?>">Agregar Categoría</a>
            </li>
        </ul>
    </div>
</header>
<!-- Contenido del index -->
<div class="container">
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

    <!-- Botón para activar el modal de añadir espacio -->
    <button class="btn btn-success mb-3" onclick="openAddModal()">Añadir Espacio de Trabajo</button>

    <!-- Tabla de espacios, con columnas ocultas en pantallas pequeñas -->
    <div class="table-responsive">
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th>Estado</th>
                    <th>Imagen</th>
                    <th>Color de Fondo</th>
                    <th>Categoría</th>
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
                        <td><?= $espacio['nombre_categoria'] ?></td>
                        <td>
                            <!-- Iconos para ver detalles, editar y eliminar -->
                            <i class="fas fa-eye text-info" title="Ver" onclick="viewDetails(<?= $espacio['id'] ?>)"></i>
                            <i class="fas fa-edit text-warning" title="Editar" onclick="editEspacio(<?= $espacio['id'] ?>)"></i>
                            <i class="fas fa-trash text-danger" title="Eliminar" onclick="confirmDelete(<?= $espacio['id'] ?>)"></i>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
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
    <div class="color-selector" id="colorPalette">
        <div class="color-circle" style="background-color: #ff5733;" data-color="#FF5733"></div>
        <div class="color-circle" style="background-color: #8e8e38;" data-color="#8E8E38"></div>
        <div class="color-circle" style="background-color: #4caf50;" data-color="#4CAF50"></div>
        <div class="color-circle" style="background-color: #008080;" data-color="#008080"></div>
        <div class="color-circle" style="background-color: #1e90ff;" data-color="#1E90FF"></div>
        <div class="color-circle" style="background-color: #6a0dad;" data-color="#6A0DAD"></div>
        <div class="color-circle" style="background-color: #ff6347;" data-color="#FF6347"></div>
    </div>
    <input type="hidden" id="color" name="color" value="#ff5733">
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
    const BASE_URL = '<?= base_url() ?>';

    // Función para abrir el modal con los detalles de un espacio de trabajo
    function viewDetails(id) {
        const url = BASE_URL + 'espacios/getDetails/' + id;
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
</body>
</html>
