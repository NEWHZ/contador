<!-- header.php -->
<?php
    // Obtener la URI actual para determinar qué enlace está activo
    $current_page = basename($_SERVER['REQUEST_URI'], ".php");
?>

<header class="d-flex align-items-center justify-content-between px-3 py-2" style="background-color: #333; border-bottom: 1px solid #444; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
    <div class="d-flex align-items-center gap-2">
        <div class="me-2">
            <svg viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg" width="40px" height="40px" aria-hidden="true" style="color: #fff;">
                <path d="M6 6H42L36 24L42 42H6L12 24L6 6Z" fill="currentColor"></path>
            </svg>
        </div>
        <h2 class="text-light m-0">Device Time</h2>
    </div>
    <div class="d-flex align-items-center gap-3">
        <nav class="d-none d-md-flex gap-4">
            <a class="nav-link <?php echo $current_page == 'historial' ? 'active' : ''; ?>" href="#" style="text-decoration: none;">Historial</a>
            <a class="nav-link <?php echo $current_page == 'espacios' ? 'active' : ''; ?>" href="/contador/index.php/espacios" style="text-decoration: none;">Dispositivos</a>
            <a class="nav-link <?php echo $current_page == 'tablero' ? 'active' : ''; ?>" href="<?php echo site_url('tablero'); ?>" style="text-decoration: none;">Show Time</a>
            <a class="nav-link <?php echo $current_page == 'asignarTiempo' ? 'active' : ''; ?>" href="<?php echo site_url('asignarTiempo'); ?>" style="text-decoration: none;">Asignar Tiempo</a>
        </nav>
    </div>
</header>
