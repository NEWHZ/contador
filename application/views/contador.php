<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contador</title>
    <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin="" /> 
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?display=swap&family=Manrope:wght@400;500;700;800&family=Noto+Sans:wght@400;500;700;900" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="<?php echo base_url('assets/css/styles.css'); ?>">
    <script src="<?php echo base_url('assets/js/timer-core.js'); ?>"></script>

    <style>
        /* Botones personalizados */
        .timer-options button {
            padding: 12px 24px;
            font-size: 1.2rem;
            font-weight: bold;
            border-radius: 25px;
            border: none;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            color: white;
        }

        .timer-options button:hover {
            transform: scale(1.05);
            box-shadow: 0 8px 12px rgba(0, 0, 0, 0.2);
        }

        .btn-success {
            background-color: #28a745;
        }

        .btn-danger {
            background-color: #dc3545;
        }

        .btn-success:hover {
            background-color: #218838;
        }

        .btn-danger:hover {
            background-color: #c82333;
        }

        /* Cambiar el color del botón Asignar Tiempo a azul oscuro */
        .btn-primary {
            background-color: #003366;
        }

        .btn-primary:hover {
            background-color: #002244;
        }

        /* Animación e imagen en el Timer Placeholder */
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
    </style>
</head>

<body>
<div class="d-flex flex-column min-vh-100">
    <?php include 'header.php'; ?>

    <main class="container-fluid flex-grow-1 py-4">
        <div class="row justify-content-center">
            <div class="col-md-10 col-lg-8">
                <h1 class="mb-4 text-center">Asignar tiempo</h1>

                <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                    <?php foreach ($espacios as $espacio): ?>
                        <div class="col">
                            <div class="card h-100" style="background-color: <?= $espacio['color_fondo'] ?>;">
                                <img src="data:image/jpeg;base64,<?= base64_encode($espacio['imagen']) ?>" class="card-img-top" alt="<?= $espacio['nombre'] ?>" />
                                <div class="card-body d-flex flex-column">
                                    <h5 class="card-title"><?= $espacio['nombre'] ?></h5>
                                    <p class="card-text flex-grow-1"><?= $espacio['descripcion'] ?></p>
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
                        <h5 class="modal-title" id="timerModalLabel">Eleccion de Timer</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body text-center">

                        <!-- Improved Image Section or Placeholder with Decorative Background -->
                        <div id="initial-content">
                            <div class="timer-image-placeholder">
                                <img src="https://cdn-icons-png.flaticon.com/512/1892/1892001.png" alt="Timer Icon" />
                            </div>
                            
                            <p class="px-3">
                            Por favor selecciona una opción de temporizador a continuación para comenzar. Puedes elegir iniciar un cronómetro o establecer una cuenta regresiva."
                            </p>

                            <!-- Aligned Buttons with Spacing -->
                            <div class="timer-options d-flex justify-content-center gap-3">
                                <button onclick="showStopwatch()" class="btn btn-success">
                                    StopWatch
                                </button>
                                <button onclick="showCountdown()" class="btn btn-danger">
                                    CountDown
                                </button>
                            </div>
                        </div>

                        <!-- Timer Controls Section -->
                        <div id="timer-controls" class="mt-4" style="display: none;">
                            <h2 id="timer-type" class="mb-4">StopWatch/CountDown</h2>
                            <div id="timer-display" class="mb-3" style="font-size: 2.5rem;">00:00:00</div>

                            <!-- Control Buttons -->
                            <div class="d-flex justify-content-center gap-2">
                                <button class="btn btn-success" id="startPauseBtn" onclick="startPauseTimer()">Empezar</button>
                                <button class="btn btn-danger" onclick="resetTimer()">Reiniciar</button>
                                <button class="btn btn-primary" onclick="terminarStopwatch()">Terminar</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </main>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> 
<script>
    const baseURL = '<?php echo base_url(); ?>';
</script>
<script src="<?php echo base_url('assets/js/timer-core.js'); ?>"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
