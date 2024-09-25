<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contador</title>
    
    <!-- Icono de la página -->
    <link rel="shortcut icon" href="<?php echo base_url('public/img/alarma-3d.png'); ?>" type="image/x-icon">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin="" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?display=swap&family=Manrope:wght@400;500;700;800&family=Noto+Sans:wght@400;500;700;900" />
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Estilos personalizados -->
    <link rel="stylesheet" href="<?php echo base_url('assets/css/styles.css'); ?>">
    <link rel="stylesheet" href="<?php echo base_url('assets/css/styles.contador.css'); ?>">
    
</head>

<body>
<div class="d-flex flex-column min-vh-100">
<?php
    // Obtener la URI actual para determinar qué enlace está activo
    $uri_segments = explode('/', parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
    $current_page = end($uri_segments); // Obtiene la última parte de la URL actual
?>

    <!-- Incluir el header -->
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

    <main class="container-fluid flex-grow-1 py-4">
        <div class="row justify-content-center">
            <div class="col-md-10 col-lg-8">
                <h1 class="mb-4 text-center">Asignar tiempo</h1>

                <!-- Filtro por categorías -->
                <div class="mb-4 text-center">
                    <label for="filtroCategoria">Filtrar por categoría:</label>
                    <select id="filtroCategoria" class="form-select" onchange="filtrarPorCategoria()">
                        <option value="todas">Todas las categorías</option>
                        <!-- Mostrar las categorías en minúsculas para que coincidan con data-categoria -->
                        <?php foreach ($categorias as $categoria): ?>
                            <option value="<?= strtolower($categoria['nombre']) ?>"><?= $categoria['nombre'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Fichas con grid -->
                <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4" id="listaEspacios">
                    <!-- Mostrar los espacios activos -->
                    <?php foreach ($espacios as $espacio): ?>
                        <!-- Aquí aseguramos que el data-categoria tenga el valor correspondiente a la categoría del espacio -->
                        <div class="col espacio-card" data-categoria="<?= strtolower($espacio['categoria']) ?>">
                            <div class="card h-100" style="background-color: <?= $espacio['color_fondo'] ?>;">
                                <img src="data:image/jpeg;base64,<?= base64_encode($espacio['imagen']) ?>" class="card-img-top" alt="<?= $espacio['nombre'] ?>" />
                                <div class="card-body d-flex flex-column">
                                    <h5 class="card-title"><?= $espacio['nombre'] ?></h5>
                                    <p class="card-text flex-grow-1"><?= $espacio['descripcion'] ?></p>
                                    <p class="card-text"><strong>Categoría:</strong> <?= $espacio['categoria'] ?></p>
                                    <button class="btn btn-primary mt-2" data-bs-toggle="modal" data-bs-target="#timerModal" onclick="openTimerModal('<?= $espacio['id'] ?>')">Asignar tiempo</button>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="timerModal" tabindex="-1" aria-labelledby="timerModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="timerModalLabel">Elección de Temporizador</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body text-center">
                        <!-- Imagen o Placeholder con fondo decorativo -->
                        <div id="initial-content">
                            <div class="timer-image-placeholder">
                                <img src="https://cdn-icons-png.flaticon.com/512/1892/1892001.png" alt="Timer Icon" />
                            </div>
                            <p class="px-3">Por favor selecciona una opción de temporizador a continuación para comenzar. Puedes elegir iniciar un cronómetro o establecer una cuenta regresiva.</p>

                            <!-- Botones alineados con espaciado -->
                            <div class="timer-options d-flex justify-content-center gap-3">
                                <button onclick="showStopwatch()" class="btn btn-success">StopWatch</button>
                                <button onclick="showCountdown()" class="btn btn-danger">CountDown</button>
                            </div>
                        </div>

                        <!-- Controles del temporizador -->
                        <div id="timer-controls" class="mt-4" style="display: none;">
                            <h2 id="timer-type" class="mb-4">StopWatch/CountDown</h2>
                            <div id="timer-display" class="mb-3" style="font-size: 2.5rem;">00:00:00</div>

                            <!-- Botones de control -->
                            <div class="d-flex justify-content-center gap-2">
                                <button class="btn btn-success" id="startPauseBtn" onclick="startPauseTimer()">Empezar</button>
                                <button class="btn btn-danger" onclick="resetTimer()">Reiniciar</button>
                                <button class="btn btn-primary" onclick="terminarStopwatch()">Terminar</button>
                            </div>
                        </div>

                        <!-- Nuevo selector de horas y minutos para Countdown -->
                        <div id="countdown-settings" style="display: none;">
                            <h5>Selecciona el tiempo máximo</h5>
                            <div class="row">
                                <div class="col">
                                    <label for="hours">Horas (0-23):</label>
                                    <select id="hours" class="form-control">
                                        <?php for ($i = 0; $i <= 23; $i++): ?>
                                            <option value="<?= $i ?>"><?= $i ?></option>
                                        <?php endfor; ?>
                                    </select>
                                </div>
                                <div class="col">
                                    <label for="minutes">Minutos (0-59):</label>
                                    <select id="minutes" class="form-control">
                                        <?php for ($i = 0; $i <= 59; $i++): ?>
                                            <option value="<?= $i ?>"><?= $i ?></option>
                                        <?php endfor; ?>
                                    </select>
                                </div>
                            </div>
                            <button onclick="setCustomTime()" class="btn btn-primary mt-2">Establecer Countdown</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </main>
</div>

<script>
// Función para filtrar por categoría
function filtrarPorCategoria() {
    var filtro = document.getElementById('filtroCategoria').value.toLowerCase();
    var espacios = document.getElementsByClassName('espacio-card');

    for (var i = 0; i < espacios.length; i++) {
        var categoria = espacios[i].getAttribute('data-categoria').toLowerCase();

        // Mostrar u ocultar las tarjetas según la categoría seleccionada
        if (filtro === 'todas' || categoria === filtro) {
            espacios[i].style.display = 'block';
        } else {
            espacios[i].style.display = 'none';
        }
    }
}
</script>

<!-- Cargar jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> 

<!-- Script del temporizador -->
<script>
    const baseURL = '<?php echo base_url(); ?>';
</script>
<script src="<?php echo base_url('assets/js/timer-core.js'); ?>"></script>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
