<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contador</title>
    <link rel="shortcut icon" href="<?php echo base_url('public/img/alarma-3d.png'); ?>" type="image/x-icon">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome for Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="<?php echo base_url('assets/css/styles.css'); ?>">
    <style>
        .container { margin-top: 50px; }

        /* Estilos responsivos para pantallas pequeñas */
        @media (max-width: 768px) {
            .table td i {
                font-size: 1.5rem;
                margin-right: 10px;
            }
        }
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
    </style>
</head>
<body>

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
<div class="container">
    <h2 class="text-center">Gestión de Categorías</h2>

    <!-- Mostrar mensajes de éxito o error -->
    <?php if ($this->session->flashdata('success')): ?>
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Éxito',
                text: '<?= $this->session->flashdata('success') ?>'
            });
        </script>
    <?php endif; ?>
    <?php if ($this->session->flashdata('error')): ?>
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: '<?= $this->session->flashdata('error') ?>'
            });
        </script>
    <?php endif; ?>

    <!-- Botón para activar el modal de añadir categoría -->
    <button class="btn btn-success mb-3" onclick="openAddModal()">Añadir Categoría</button>

    <!-- Tabla de categorías -->
    <div class="table-responsive">
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nombre</th>
                    <th>Precio</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($categorias as $categoria): ?>
                    <tr>
                        <td><?= $categoria['id'] ?></td>
                        <td><?= $categoria['nombre'] ?></td>
                        <td><?= $categoria['precio'] ?></td>
                        <td>
                            <!-- Iconos de acciones -->
                            <i class="fas fa-edit text-warning" title="Editar" onclick="editCategoria(<?= $categoria['id'] ?>)"></i>
                            <i class="fas fa-trash text-danger" title="Eliminar" onclick="deleteCategoria(<?= $categoria['id'] ?>)"></i>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal para agregar/editar categoría -->
<div class="modal fade" id="categoriaModal" tabindex="-1" aria-labelledby="categoriaModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="categoriaModalLabel">Añadir Categoría</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="categoriaForm" method="POST">
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre de la Categoría</label>
                        <input type="text" class="form-control" id="nombre" name="nombre" required>
                    </div>
                    <div class="mb-3">
                        <label for="precio" class="form-label">Precio</label>
                        <input type="number" class="form-control" id="precio" name="precio" step="0.01" required>
                    </div>
                    <input type="hidden" id="categoriaId" name="categoriaId">
                    <div class="text-center">
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Pasar la URL base a JavaScript de manera segura -->
<script>
    const BASE_URL = '<?= base_url(); ?>';
</script>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<!-- Custom JS -->
<script src="<?= base_url('assets/js/categoria.js') ?>"></script>
<?php include APPPATH . 'views/widget/weather.php'; ?>
</body>
</html>
