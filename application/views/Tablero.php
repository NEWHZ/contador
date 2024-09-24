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
<div class="d-flex flex-column min-vh-100">
<?php
    // Obtener la URI actual para determinar qué enlace está activo
    $uri_segments = explode('/', parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
    $current_page = end($uri_segments); // Obtiene la última parte de la URL actual
?>

    <!-- Incluir el header -->
    <?php $this->load->view('header'); ?>  <!-- Incluir el header -->
    
    <main class="container-fluid flex-grow-1 py-4">
        <h1 class="mb-4 text-center">Tablero de Tiempos</h1>
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4" id="spaces-container">
            <?php foreach ($espacios as $espacio): ?>
                <div class="col">
                    <div class="card h-100" style="background-color: <?= $espacio['color_fondo'] ?>;">
                        <img src="data:image/jpeg;base64,<?= base64_encode($espacio['imagen']) ?>" class="card-img-top" alt="<?= $espacio['nombre'] ?>" />
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title"><?= $espacio['nombre'] ?></h5>
                            <p class="card-text timer-display" id="timer-display-<?= $espacio['id'] ?>">Cargando...</p>
                            <p class="text-muted" id="timer-status-<?= $espacio['id'] ?>">Cargando...</p>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </main>
</div>

<!-- Agregar un archivo de sonido de alarma -->
<audio id="alarm-sound" src="https://www.soundjay.com/button/beep-07.wav" preload="auto"></audio>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        <?php foreach ($espacios as $espacio): ?>
            (function() {
                const spaceId = '<?= $espacio['id'] ?>';

                // Verificar si la alarma ya fue disparada para este espacio en localStorage
                let alarmTriggered = localStorage.getItem(`alarmTriggered_${spaceId}`) === "true";

                // Función que actualiza el display de tiempo para un espacio específico
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

                        // Si está en pausa, mostrar el tiempo al momento de la pausa
                        if (isPaused && pausedAt) {
                            elapsedTime = Math.floor((pausedAt - startTime) / 1000);
                        }

                        if (isStopwatch) {
                            displayTime = formatTime(elapsedTime);
                        } else {
                            const remainingTime = countdownTime - elapsedTime;
                            displayTime = formatTime(Math.max(remainingTime, 0));

                            // Si el tiempo restante es 0 o menor y la alarma aún no se ha disparado
                            if (remainingTime <= 0 && !alarmTriggered) {
                                triggerAlarm(); // Mostrar la alarma
                                // Guardar en localStorage que la alarma ya fue disparada solo después de que se dispara
                                alarmTriggered = true; 
                                localStorage.setItem(`alarmTriggered_${spaceId}`, "true");
                            }
                        }
                    }

                    // Actualizar la visualización de tiempo en el tablero
                    document.getElementById(`timer-display-${spaceId}`).textContent = displayTime;
                    document.getElementById(`timer-status-${spaceId}`).textContent = startTime > 0 ? (isPaused ? 'Pausado' : 'En curso') : 'Inactivo';
                }

                refreshDisplay();

                // Inicia el intervalo para actualizar el display en tiempo real cada segundo
                setInterval(() => {
                    refreshDisplay();
                }, 1000);

                // Función para mostrar la alarma usando SweetAlert y reproducir sonido
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
