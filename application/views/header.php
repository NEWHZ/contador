<!-- header.php -->
<?php
    // Obtener la URI actual para determinar qué enlace está activo
    $uri_segments = explode('/', parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
    $current_page = end($uri_segments);
?>

<header class="d-flex align-items-center justify-content-between border-bottom px-3 py-2 bg-dark">
    <!-- Botón de menú principal -->
    <div class="dropdown">
        <button class="btn btn-outline-light dropdown-toggle" type="button" id="mainMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
            <!-- Ícono de menú hamburguesa -->
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="24" height="24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7" />
            </svg>
        </button>

        <!-- Menú desplegable -->
        <ul class="dropdown-menu" aria-labelledby="mainMenuButton">
            <li><a class="dropdown-item <?php echo $current_page == 'alquiler' ? 'active' : ''; ?>" href="/contador/index.php/alquiler">Historial</a></li>
            <li><a class="dropdown-item <?php echo $current_page == 'espacios' ? 'active' : ''; ?>" href="/contador/index.php/espacios">Dispositivos</a></li>
            <li><a class="dropdown-item <?php echo $current_page == 'tablero' ? 'active' : ''; ?>" href="<?php echo site_url('tablero'); ?>">Show Time</a></li>
            <li><a class="dropdown-item <?php echo $current_page == 'asignarTiempo' ? 'active' : ''; ?>" href="<?php echo site_url('asignarTiempo'); ?>">Asignar Tiempo</a></li>
        </ul>
    </div>

    <!-- Título del sistema al lado derecho -->
    <div class="d-flex align-items-center">
        <h2 class="text-light m-0">Device Time</h2>
    </div>
</header>
