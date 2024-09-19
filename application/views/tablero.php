<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tablero de Tiempos</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo base_url('assets/css/styles.css'); ?>"> <!-- Include styles.css -->
    <style>
        /* Additional styling adjustments to fit the provided styles */
        .card {
            height: 150px;
            width:450px; /* Adjusted card height */
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
        .button-container {
            display: flex;
            justify-content: flex-start; /* Change to center or flex-end as needed */
            margin-left: 20px; /* Adjust this value to move the button */
        }

        .btn-primary {
            margin-top: auto;
            margin-left: 160px;
        }

        /* Reduce the default margin to make better use of screen space */
        .container-fluid {
            padding-left: 8rem;
            padding-right: 8rem;
        }
        header {
        background-color: #333; /* Fondo oscuro */
        border-bottom: 1px solid #444; /* Borde inferior */
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); /* Sombra del header */
    }

    header .nav-link {
        color: #fff; /* Color del texto */
        padding: 8px 12px;
        transition: background-color 0.3s, color 0.3s; /* Transición suave */
    }

    header .nav-link:hover {
        background-color: #555; /* Color de fondo al hacer hover */
    }

    header .nav-link.active {
        background-color: #28a745; /* Fondo verde para el enlace activo */
        color: #fff;
        border-radius: 4px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); /* Sombra para el enlace activo */
    }

    header h2 {
        color: #fff; /* Color del título */
    }
    </style>
</head>
<body>
<div class="d-flex flex-column min-vh-100">
    <!-- Include the header from contador.php -->
    <?php include 'header.php'; ?>
    
    <main class="container-fluid flex-grow-1 py-4">
        <h1 class="mb-4 text-center">Tablero de Tiempos</h1>
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4" id="spaces-container">
            <?php foreach ($espacios as $espacio): ?>
                <div class="col">
                    <div class="card h-100" style="background-color: <?= $espacio['color_fondo'] ?>;">
                        <img src="data:image/jpeg;base64,<?= base64_encode($espacio['imagen']) ?>" class="card-img-top" alt="<?= $espacio['nombre'] ?>" />
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title"><?= $espacio['nombre'] ?></h5>
                            <p class="card-text timer-display" id="timer-display-<?= $espacio['id'] ?>">No se ha configurado el tiempo</p>
                            <p class="text-muted" id="timer-status-<?= $espacio['id'] ?>">Inactivo</p>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </main>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Loop through each espacio and retrieve its corresponding data
        <?php foreach ($espacios as $espacio): ?>
            (function() {
                const spaceId = '<?= $espacio['id'] ?>';

                // Retrieve stored values for each space
                const isStopwatch = localStorage.getItem(`isStopwatch_${spaceId}`) === "true";
                const startTime = parseInt(localStorage.getItem(`startTime_${spaceId}`)) || 0;
                const storedTimeElapsed = parseInt(localStorage.getItem(`timeElapsed_${spaceId}`)) || 0;
                const storedCountdownTime = parseInt(localStorage.getItem(`countdownTime_${spaceId}`)) || 0;
                const isPaused = localStorage.getItem(`isPaused_${spaceId}`) === "true";

                let displayTime;

                // Determine the initial display state
                if (isStopwatch || (!isStopwatch && storedCountdownTime > 0)) {
                    if (startTime && !isPaused) {
                        const currentTime = new Date().getTime();
                        let elapsedTime = Math.floor((currentTime - startTime) / 1000);

                        if (isStopwatch) {
                            displayTime = storedTimeElapsed + elapsedTime;
                        } else {
                            displayTime = storedCountdownTime - elapsedTime;
                            if (displayTime < 0) displayTime = 0; // Prevent negative time
                        }
                    } else {
                        displayTime = isStopwatch ? storedTimeElapsed : storedCountdownTime;
                    }

                    // Update the display on the page
                    document.getElementById(`timer-display-${spaceId}`).textContent = formatTime(displayTime);
                    document.getElementById(`timer-status-${spaceId}`).textContent = isPaused ? 'Pausado' : 'En curso';

                    // If the timer is running, update it every second
                    if (!isPaused && startTime) {
                        updateTimer(spaceId, isStopwatch, displayTime);
                    }
                } else {
                    resetTimerDisplay(spaceId); // If no valid timer, reset display
                }
            })();
        <?php endforeach; ?>
    });

    // Function to format time in HH:MM:SS
    function formatTime(seconds) {
        if (isNaN(seconds) || seconds < 0) return "00:00:00"; // Default value if seconds is NaN or less than 0
        const hrs = Math.floor(seconds / 3600);
        const mins = Math.floor((seconds % 3600) / 60);
        const secs = seconds % 60;
        return `${hrs.toString().padStart(2, '0')}:${mins.toString().padStart(2, '0')}:${secs.toString().padStart(2, '0')}`;
    }

    // Function to update the timer display
    function updateTimer(spaceId, isStopwatch, initialDisplayTime) {
        let displayTime = initialDisplayTime;
        const timerInterval = setInterval(() => {
            if (isStopwatch) {
                displayTime++; // Increment for Stopwatch
            } else {
                displayTime--; // Decrement for Countdown
            }

            if (displayTime < 0) {
                clearInterval(timerInterval);
                displayTime = 0; // Prevent negative time for countdown
                resetTimerDisplay(spaceId); // Reset to default state
            }

            document.getElementById(`timer-display-${spaceId}`).textContent = formatTime(displayTime);
            localStorage.setItem(`timeElapsed_${spaceId}`, displayTime);
        }, 1000);
    }

    // Function to reset the timer display to its initial state
    function resetTimerDisplay(spaceId) {
        document.getElementById(`timer-display-${spaceId}`).textContent = 'No se ha configurado el tiempo';
        document.getElementById(`timer-status-${spaceId}`).textContent = 'Inactivo';
        
        // Reset local storage values
        localStorage.removeItem(`startTime_${spaceId}`);
        localStorage.removeItem(`timeElapsed_${spaceId}`);
        localStorage.removeItem(`isPaused_${spaceId}`);
        localStorage.removeItem(`countdownTime_${spaceId}`);
    }
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
