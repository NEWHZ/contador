<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contador</title>
    <link rel="shortcut icon" href="<?php echo base_url('public/img/alarma-3d.png'); ?>" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo base_url('assets/css/styles.css'); ?>">
    <script src="<?php echo base_url('assets/js/timer-core.js'); ?>"></script>

    <!-- Incluir SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        .card {
            height: 150px;
            width: 450px;
            display: flex;
            flex-direction: column;
            margin-left: 50px;
        }
        .card-body {
            text-align: center;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        .timer-display {
            font-size: 18px;
            margin-top: 10px;
            font-weight: bold;
        }
        .card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }
        .container-fluid {
            padding-left: 8rem;
            padding-right: 8rem;
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
<div class="d-flex flex-column min-vh-100" id="mainContainer">
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
        <h1 class="mb-4 text-center">Tablero de Tiempos</h1>

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

        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4" id="spaces-container">
            <?php foreach ($espacios as $espacio): ?>
                <!-- Asegurarse de que las tarjetas tengan un valor correcto de data-categoria -->
                <div class="col espacio-card" data-categoria="<?= strtolower($espacio['categoria']) ?>">
                    <div class="card h-100" style="background-color: <?= $espacio['color_fondo'] ?>;">
                        <img src="data:image/jpeg;base64,<?= base64_encode($espacio['imagen']) ?>" class="card-img-top" alt="<?= $espacio['nombre'] ?>" />
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title"><?= $espacio['nombre'] ?></h5>
                            <p class="card-text flex-grow-1"><?= $espacio['descripcion'] ?></p>
                            <!-- Mostrar la categoría aquí -->
                            <p class="card-text"><strong>Categoría:</strong> <?= $espacio['categoria'] ?></p>
                            <p class="card-text timer-display" id="timer-display-<?= $espacio['id'] ?>">Cargando...</p>
                            <p class="text-muted" id="timer-status-<?= $espacio['id'] ?>">Cargando...</p>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </main>
</div>

<audio id="alarm-sound" src="https://www.soundjay.com/button/beep-07.wav" preload="auto"></audio>

<script>
// Función para filtrar por categoría y cambiar el fondo
function filtrarPorCategoria() {
    var filtro = document.getElementById('filtroCategoria').value.toLowerCase();
    var espacios = document.getElementsByClassName('espacio-card');
    var mainContainer = document.getElementById('mainContainer'); // Seleccionar el contenedor principal
    var colorAsignado = false; // Variable para asignar el color solo una vez

    // Recorrer los espacios para mostrar/ocultar y cambiar el fondo
    for (var i = 0; i < espacios.length; i++) {
        var categoria = espacios[i].getAttribute('data-categoria').toLowerCase();
        var cardColor = window.getComputedStyle(espacios[i].querySelector('.card')).backgroundColor;

        // Mostrar u ocultar las tarjetas según la categoría seleccionada
        if (filtro === 'todas' || categoria === filtro) {
            espacios[i].style.display = 'block';

            // Si es la primera tarjeta que coincide y aún no se ha asignado un color, usar su color para el fondo
            if (!colorAsignado && filtro !== 'todas') {
                mainContainer.style.backgroundColor = lightenColor(cardColor, 40); // Cambiar a un color más claro
                colorAsignado = true; // Marcar que ya se ha asignado el color
            }
        } else {
            espacios[i].style.display = 'none';
        }
    }

    // Si no hay tarjetas visibles, volver al color por defecto
    if (!colorAsignado) {
        mainContainer.style.backgroundColor = '#fff'; // O el color de fondo que prefieras
    }
}

// Función para aclarar un color
function lightenColor(color, percent) {
    var rgbValues = color.match(/\d+/g).map(Number); // Extraer valores RGB

    var r = Math.min(255, Math.round(rgbValues[0] + (255 - rgbValues[0]) * percent / 100));
    var g = Math.min(255, Math.round(rgbValues[1] + (255 - rgbValues[1]) * percent / 100));
    var b = Math.min(255, Math.round(rgbValues[2] + (255 - rgbValues[2]) * percent / 100));

    return 'rgb(' + r + ',' + g + ',' + b + ')';
}

document.addEventListener('DOMContentLoaded', function () {
    <?php foreach ($espacios as $espacio): ?>
        (function() {
            const spaceId = '<?= $espacio['id'] ?>';
            let alarmTriggered = localStorage.getItem(`alarmTriggered_${spaceId}`) === "true";

            function refreshDisplay() {
                const isStopwatch = localStorage.getItem(`isStopwatch_${spaceId}`) === "true";
                const startTime = parseInt(localStorage.getItem(`startTime_${spaceId}`)) || 0;
                const countdownTime = parseInt(localStorage.getItem(`countdownTime_${spaceId}`)) || 0;
                const isPaused = localStorage.getItem(`isPaused_${spaceId}`) === "true";
                const pausedAt = parseInt(localStorage.getItem(`pausedAt_${spaceId}`)) || null;

                let displayTime = "00:00:00";

                if (startTime > 0) {
                    const currentTime = new Date().getTime();
                    let elapsedTime = Math.floor((currentTime - startTime) / 1000);

                    if (isPaused && pausedAt) {
                        elapsedTime = Math.floor((pausedAt - startTime) / 1000);
                    }

                    if (isStopwatch) {
                        displayTime = formatTime(elapsedTime);
                    } else {
                        const remainingTime = countdownTime - elapsedTime;
                        displayTime = formatTime(Math.max(remainingTime, 0));

                        if (remainingTime <= 0 && !alarmTriggered) {
                            triggerAlarm(); 
                            alarmTriggered = true; 
                            localStorage.setItem(`alarmTriggered_${spaceId}`, "true");
                        }
                    }
                }

                document.getElementById(`timer-display-${spaceId}`).textContent = displayTime;
                document.getElementById(`timer-status-${spaceId}`).textContent = startTime > 0 ? (isPaused ? 'Pausado' : 'En curso') : 'Inactivo';
            }

            refreshDisplay();

            setInterval(() => {
                refreshDisplay();
            }, 1000);

            function triggerAlarm() {
                const alarmSound = document.getElementById('alarm-sound');
                alarmSound.play();

                Swal.fire({
                    title: '¡Tiempo terminado!',
                    text: 'El tiempo del espacio ha llegado a su fin.',
                    icon: 'warning',
                    confirmButtonText: 'Aceptar'
                });
            }
        })();
    <?php endforeach; ?>
});

// Función para formatear el tiempo en HH:MM:SS
function formatTime(seconds) {
    if (isNaN(seconds) || seconds < 0) return "00:00:00";
    const hrs = Math.floor(seconds / 3600);
    const mins = Math.floor((seconds % 3600) / 60);
    const secs = seconds % 60;
    return `${hrs.toString().padStart(2, '0')}:${mins.toString().padStart(2, '0')}:${secs.toString().padStart(2, '0')}`;
}
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
