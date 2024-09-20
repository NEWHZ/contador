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
        .card {
            height: auto;
            min-height: 300px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            margin-bottom: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .card-body {
            text-align: center;
            padding: 15px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .card img {
            max-height: 150px;
            object-fit: cover;
            border-bottom: 1px solid #ddd;
        }

        .card-title {
            font-size: 1.25rem;
            font-weight: bold;
            color: #333;
        }

        .card-text {
            font-size: 1rem;
            color: #555;
            margin-bottom: 10px;
        }

        .btn-primary {
            background-color: #0f4c75;
            border-color: #0f4c75;
            margin-top: auto;
        }

        .btn-primary:hover {
            background-color: #3282b8;
            border-color: #3282b8;
        }

        #timerModal .modal-header {
            background-color: #0f4c75;
            color: white;
        }

        #timerModal .modal-title {
            font-size: 1.5rem;
            font-weight: 700;
        }

        #timerModal .modal-body {
            padding: 20px;
        }

        #timerModal .btn-success {
            background-color: #28a745;
            border-color: #28a745;
        }

        #timerModal .btn-warning {
            background-color: #ffc107;
            border-color: #ffc107;
        }

        #timerModal .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }

        #timerModal .btn-danger {
            background-color: #dc3545;
            border-color: #dc3545;
        }

        #timer-display {
            font-size: 2rem;
            font-weight: bold;
            text-align: center;
            margin-bottom: 20px;
        }

        .timer-options {
            display: flex;
            justify-content: center;
            gap: 10px;
        }

        .modal-body .btn {
            margin: 5px;
            width: 100px;
        }

        .flex-column {
            display: flex;
            flex-direction: column;
        }

        .container-fluid {
            padding-left: 1.5rem;
            padding-right: 1.5rem;
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
                        <h5 class="modal-title" id="timerModalLabel">Choose Timer</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div id="initial-content">
                            <img src="image.png" alt="Timer Image" />
                            <p>Please select a timer option below to begin. You can either choose to start a Stopwatch or set a Countdown.</p>
                            <div class="timer-options">
                                <button onclick="showStopwatch()" class="btn btn-success">StopWatch</button>
                                <button onclick="showCountdown()" class="btn btn-danger">CountDown</button>
                            </div>
                        </div>

                        <div id="timer-controls">
                            <h2 id="timer-type">StopWatch/CountDown</h2>
                            <div id="timer-display">00:00:00</div>
                            <button class="btn btn-success" id="startPauseBtn" onclick="startPauseTimer()">Empezar</button>
                            <button class="btn btn-danger" onclick="resetTimer()">Reiniciar</button>
                            <button class="btn btn-primary" onclick="terminarStopwatch()">Terminar</button>
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
