<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contador</title>
    <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin="" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?display=swap&family=Manrope:wght@400;500;700;800&family=Noto+Sans:wght@400;500;700;900" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }

        .card-title {
            font-weight: bold;
        }

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

        /* Responsiveness */
        @media (max-width: 768px) {
            .card img {
                height: 150px;
            }
        }
    </style>
</head>

<body>
<div class="d-flex flex-column min-vh-100">
    <header class="d-flex align-items-center justify-content-between border-bottom px-3 py-2">
        <div class="d-flex align-items-center gap-2">
            <div class="me-2">
                <svg viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg" width="40px" height="40px">
                    <path d="M6 6H42L36 24L42 42H6L12 24L6 6Z" fill="currentColor"></path>
                </svg>
            </div>
            <h2 class="text-dark m-0">Device Time</h2>
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
                            <div class="card h-100">
                                <img src="data:image/jpeg;base64,<?= base64_encode($espacio['imagen']) ?>" class="card-img-top" alt="<?= $espacio['nombre'] ?>" />
                                <div class="card-body d-flex flex-column">
                                    <h5 class="card-title"><?= $espacio['nombre'] ?></h5>
                                    <p class="card-text flex-grow-1"><?= $espacio['descripcion'] ?></p>
                                    <!-- Botón para abrir el modal -->
                                    <button class="btn btn-primary mt-2" data-bs-toggle="modal" data-bs-target="#timerModal">Asignar tiempo</button>
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
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

<script>
    // Código para el temporizador
    let timerInterval;
    let isStopwatch = false;
    let timeElapsed = 0;
    let countdownTime = 300; // 5 minutos por defecto
    let isPaused = true;

    // Cargar el estado desde localStorage cuando se carga la página
    window.onload = function () {
        const storedTime = localStorage.getItem("timeElapsed");
        const storedPausedState = localStorage.getItem("isPaused");
        const storedIsStopwatch = localStorage.getItem("isStopwatch");

        if (storedTime !== null) {
            timeElapsed = parseInt(storedTime);
            isPaused = storedPausedState === "true";
            isStopwatch = storedIsStopwatch === "true";
            document.getElementById("initial-content").style.display = "none";
            document.getElementById("timer-controls").style.display = "block";
            document.getElementById("timer-type").textContent = isStopwatch ? "StopWatch Active" : "Countdown Active";
            document.getElementById("timer-display").textContent = formatTime(timeElapsed);

            if (isPaused) {
                document.getElementById("startPauseBtn").textContent = "Continuar";
                document.getElementById("startPauseBtn").classList.replace("btn-success", "btn-primary");
            } else {
                document.getElementById("startPauseBtn").textContent = "Pausar";
                document.getElementById("startPauseBtn").classList.replace("btn-success", "btn-warning");
                startTimer();
            }
        }
    };

    function showStopwatch() {
        document.getElementById("initial-content").style.display = "none";
        document.getElementById("timer-controls").style.display = "block";
        document.getElementById("timer-type").textContent = "StopWatch Active";
        document.getElementById("timer-display").textContent = "00:00:00";
        isStopwatch = true;
        timeElapsed = 0;
        localStorage.setItem("isStopwatch", "true");
    }

    function showCountdown() {
        document.getElementById("initial-content").style.display = "none";
        document.getElementById("timer-controls").style.display = "block";
        document.getElementById("timer-type").textContent = "Countdown Active";
        document.getElementById("timer-display").textContent = "05:00";
        isStopwatch = false;
        timeElapsed = countdownTime;
        localStorage.setItem("isStopwatch", "false");
    }

    function startPauseTimer() {
        const startPauseBtn = document.getElementById("startPauseBtn");

        if (isPaused) {
            startTimer();
            startPauseBtn.textContent = "Pausar";
            startPauseBtn.classList.replace("btn-primary", "btn-warning");
            isPaused = false;
        } else {
            pauseTimer();
            startPauseBtn.textContent = "Continuar";
            startPauseBtn.classList.replace("btn-warning", "btn-primary");
            isPaused = true;
        }
        localStorage.setItem("isPaused", isPaused);
    }

    function startTimer() {
        if (timerInterval) clearInterval(timerInterval);
        timerInterval = setInterval(() => {
            if (isStopwatch) {
                timeElapsed++;
                document.getElementById("timer-display").textContent = formatTime(timeElapsed);
            } else {
                if (timeElapsed > 0) {
                    timeElapsed--;
                    document.getElementById("timer-display").textContent = formatTime(timeElapsed);
                } else {
                    clearInterval(timerInterval);
                    alert("Countdown finished!");
                    resetTimer();
                }
            }
            localStorage.setItem("timeElapsed", timeElapsed);
        }, 1000);
    }

    function pauseTimer() {
        clearInterval(timerInterval);
    }

    function resetTimer() {
        clearInterval(timerInterval);
        localStorage.removeItem("timeElapsed");
        localStorage.removeItem("isPaused");
        localStorage.removeItem("isStopwatch");
        timeElapsed = isStopwatch ? 0 : countdownTime;
        document.getElementById("timer-display").textContent = isStopwatch ? "00:00:00" : formatTime(countdownTime);
        document.getElementById("startPauseBtn").textContent = "Empezar";
        document.getElementById("startPauseBtn").classList.replace("btn-primary", "btn-success");
        isPaused = true;
    }

    function formatTime(seconds) {
        const hrs = Math.floor(seconds / 3600);
        const mins = Math.floor((seconds % 3600) / 60);
        const secs = seconds % 60;
        return `${hrs.toString().padStart(2, "0")}:${mins.toString().padStart(2, "0")}:${secs.toString().padStart(2, "0")}`;
    }
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
