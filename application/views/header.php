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
            <!-- Enlace al historial de alquiler -->
            <li class="nav-item">
                <a class="nav-link <?php echo $current_page == 'alquiler' ? 'active' : ''; ?>" href="/contador/index.php/alquiler">Historial</a>
            </li>

            <!-- Enlace a los dispositivos (espacios) -->
            <li class="nav-item">
                <a class="nav-link <?php echo $current_page == 'espacios' ? 'active' : ''; ?>" href="/contador/index.php/espacios">Dispositivos</a>
            </li>

            <!-- Enlace a "Show Time" -->
            <li class="nav-item">
                <a class="nav-link <?php echo $current_page == 'tablero' ? 'active' : ''; ?>" href="<?php echo site_url('tablero'); ?>">Show Time</a>
            </li>

            <!-- Enlace a "Asignar Tiempo" -->
            <li class="nav-item">
                <a class="nav-link <?php echo $current_page == 'asignarTiempo' ? 'active' : ''; ?>" href="<?php echo site_url('asignarTiempo'); ?>">Asignar Tiempo</a>
            </li>

            <!-- Enlace para "Agregar Categoría" -->
            <li class="nav-item">
                <a class="nav-link <?php echo $current_page == 'categorias' ? 'active' : ''; ?>" href="<?php echo site_url('categorias'); ?>">Agregar Categoría</a>
            </li>

            <!-- Mostrar enlace de "Gestión de Usuarios" solo para administradores -->
            <?php if ($this->session->userdata('role_id') == 1): ?>
            <li class="nav-item">
                <a class="nav-link <?php echo $current_page == 'usuarios' ? 'active' : ''; ?>" href="<?php echo site_url('usuarios'); ?>">Gestión de Usuarios</a>
            </li>
            <?php endif; ?>

            <!-- Enlace para cerrar sesión -->
            <li class="nav-item">
                <a class="nav-link" href="<?php echo site_url('auth/logout'); ?>">Cerrar Sesión</a>
            </li>
        </ul>
    </div>
</header>
