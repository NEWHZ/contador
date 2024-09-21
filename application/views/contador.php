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
    
    <!-- Estilos personalizados -->
    <link rel="stylesheet" href="<?php echo base_url('assets/css/styles.css'); ?>">

    <style>
        /* Estilos de las cartas de espacios */
        .card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }

        .card-title {
            font-weight: bold;
        }

        /* Estilos de los botones del temporizador */
        .timer-options {
            text-align: center;
        }

        .timer-options button {
            margin: 10px;
            padding: 10px 20px;
            font-size: 16px;
        }

        #timer-controls {
            text-align: center;
            display: none;
        }

        #timer-display {
            font-size: 32px;
            padding: 10px;
            margin-bottom: 20px;
        }

        /* Estilos personalizados para botones */
        .btn-primary {
            background-color: #003366;
        }

        .btn-primary:hover {
            background-color: #002244;
        }

        .btn-success {
            background-color: #28a745;
        }

        .btn-success:hover {
            background-color: #218838;
        }

        .btn-danger {
            background-color: #dc3545;
        }

        .btn-danger:hover {
            background-color: #c82333;
        }

        /* Animación en el Placeholder del temporizador */
        .timer-image-placeholder {
            background: linear-gradient(135deg, #3282b8, #0f4c75);
            color: white;
            font-size: 1.5rem;
            font-weight: bold;
            text-align: center;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 150px;
            margin-bottom: 20px;
            border-radius: 8px;
            animation: pulse 2s infinite;
        }

        .timer-image-placeholder img {
            width: 80px;
            height: 80px;
        }

        @keyframes pulse {
            0% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.05);
            }
            100% {
                transform: scale(1);
            }
        }

        /* Responsividad */
        @media (max-width: 768px) {
            .card img {
                height: 150px;
            }
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
    <header class="navbar navbar-expand-md navbar-dark bg-dark px-3 py-2">
        <a class="navbar-brand" href="#">Device Time</a>
        <!-- Botón hamburguesa para pantallas pequeñas -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMenu" aria-controls="navbarMenu" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarMenu">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link <?php echo $current_page == 'espacios' ? 'active' : ''; ?>" href="/contador/index.php/espacios">Dispositivos</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo $current_page == 'tablero' ? 'active' : ''; ?>" href="<?php echo site_url('tablero'); ?>">Show Time</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo $current_page == 'asignarTiempo' ? 'active' : ''; ?>" href="<?php echo site_url('asignarTiempo'); ?>">Asignar Tiempo</a>
                </li>
            </ul>
        </div>
    </header>

    <main class="container-fluid flex-grow-1 py-4">
        <div class="row justify-content-center">
            <div class="col-md-10 col-lg-8">
                <h1 class="mb-4 text-center">Asignar tiempo</h1>

                <!-- Fichas con grid -->
                <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                    <!-- Mostrar los espacios activos -->
                    <?php foreach ($espacios as $espacio): ?>
                        <div class="col">
                            <div class="card h-100" style="background-color: <?= $espacio['color_fondo'] ?>;">
                                <img src="data:image/jpeg;base64,<?= base64_encode($espacio['imagen']) ?>" class="card-img-top" alt="<?= $espacio['nombre'] ?>" />
                                <div class="card-body d-flex flex-column">
                                    <h5 class="card-title"><?= $espacio['nombre'] ?></h5>
                                    <p class="card-text flex-grow-1"><?= $espacio['descripcion'] ?></p>
                                    <!-- Botón para abrir el modal -->
                                    <button class="btn btn-primary mt-2" data-bs-toggle="modal" data-bs-target="#timerModal" onclick="openTimerModal('<?= $espacio['id'] ?>')">Asignar tiempo</button>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
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
                            
                            <p class="px-3">
                            Por favor selecciona una opción de temporizador a continuación para comenzar. Puedes elegir iniciar un cronómetro o establecer una cuenta regresiva.
                            </p>

                            <!-- Botones alineados con espaciado -->
                            <div class="timer-options d-flex justify-content-center gap-3">
                                <button onclick="showStopwatch()" class="btn btn-success">
                                    StopWatch
                                </button>
                                <button onclick="showCountdown()" class="btn btn-danger">
                                    CountDown
                                </button>
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
