let timerInterval;
let isStopwatch = false;
let timeElapsed = 0;
let countdownTime = 0; // El valor predeterminado eliminado
let isPaused = true;
let currentSpaceId = null; // ID de la ficha actual
let pausedAt = null; // Hora en la que se pausó

// Función que abre el modal con el temporizador correspondiente a una tarjeta
function openTimerModal(spaceId) {
	currentSpaceId = spaceId;

	// Cargar el estado del temporizador desde localStorage
	const storedStartTime = localStorage.getItem(`startTime_${spaceId}`);
	const storedPausedState = localStorage.getItem(`isPaused_${spaceId}`);
	const storedIsStopwatch = localStorage.getItem(`isStopwatch_${spaceId}`);
	const storedTimeElapsed = localStorage.getItem(`timeElapsed_${spaceId}`);
	const storedPausedAt = localStorage.getItem(`pausedAt_${spaceId}`);
	const storedCountdownTime = localStorage.getItem(`countdownTime_${spaceId}`);

	// Restablecer la UI del modal
	document.getElementById("initial-content").style.display = "block";
	document.getElementById("timer-controls").style.display = "none";

	// Si hay un preset duplicado, eliminarlo
	const presetContainer = document.getElementById("preset-container");
	if (presetContainer) presetContainer.remove();

	if (storedStartTime !== null) {
		timeElapsed = parseInt(storedTimeElapsed) || 0;
		countdownTime = parseInt(storedCountdownTime) || 0;
		pausedAt = storedPausedAt ? parseInt(storedPausedAt) : null;
		isPaused = storedPausedState === "true";
		isStopwatch = storedIsStopwatch === "true";

		document.getElementById("initial-content").style.display = "none";
		document.getElementById("timer-controls").style.display = "block";
		document.getElementById("timer-type").textContent = isStopwatch
			? "StopWatch Active"
			: "Countdown Active";

		// Mostrar el tiempo actual inmediatamente sin esperar
		updateDisplay();

		// Si no está pausado, continuar calculando el tiempo
		if (!isPaused) {
			startTimer(); // Iniciar el temporizador
			document.getElementById("startPauseBtn").textContent = "Pausar";
			document
				.getElementById("startPauseBtn")
				.classList.remove("btn-success", "btn-primary");
			document.getElementById("startPauseBtn").classList.add("btn-warning");
		} else {
			document.getElementById("startPauseBtn").textContent = "Continuar";
			document
				.getElementById("startPauseBtn")
				.classList.remove("btn-success", "btn-warning");
			document.getElementById("startPauseBtn").classList.add("btn-primary");
		}
	} else {
		resetTimer();
	}
}

// Muestra las opciones del cronómetro
function showStopwatch() {
	document.getElementById("initial-content").style.display = "none";
	document.getElementById("timer-controls").style.display = "block";
	document.getElementById("timer-type").textContent = "StopWatch Active";
	isStopwatch = true;
	isPaused = true;
	timeElapsed = 0; // Inicia en 0
	localStorage.setItem(`isStopwatch_${currentSpaceId}`, "true");
	localStorage.setItem(`timeElapsed_${currentSpaceId}`, timeElapsed);
}

// Muestra las opciones del countdown con presets
function showCountdown() {
	document.getElementById("initial-content").style.display = "none";
	document.getElementById("timer-controls").style.display = "block";
	document.getElementById("timer-type").textContent = "Countdown Active";
	isStopwatch = false;

	// Mostrar un formulario para ingresar tiempo o seleccionar un preset solo si no se ha mostrado antes
	if (!document.getElementById("preset-container")) {
		const presetContainer = document.createElement("div");
		presetContainer.id = "preset-container"; // Añadimos un ID para verificar duplicados
		presetContainer.innerHTML = `
            <div>
                <h5>Select a Preset</h5>
                <button onclick="setCountdown(300)" class="btn btn-info">5 Minutes</button>
                <button onclick="setCountdown(600)" class="btn btn-info">10 Minutes</button>
                <button onclick="setCountdown(1800)" class="btn btn-info">30 Minutes</button>
            </div>
            <div class="mt-3">
                <h5>Or Enter Custom Time (seconds)</h5>
                <input type="number" id="customCountdown" class="form-control" placeholder="Enter time in seconds">
                <button onclick="setCustomCountdown()" class="btn btn-primary mt-2">Set Countdown</button>
            </div>
        `;
		document.getElementById("timer-controls").appendChild(presetContainer);
	}
}

// Establecer un tiempo de cuenta regresiva de los presets
function setCountdown(seconds) {
	countdownTime = seconds;
	timeElapsed = 0; // Reset timeElapsed since we're starting fresh
	isPaused = true;
	localStorage.setItem(`isStopwatch_${currentSpaceId}`, "false");
	localStorage.setItem(`countdownTime_${currentSpaceId}`, countdownTime);
	updateDisplay();
}

// Establecer un tiempo de cuenta regresiva personalizado
function setCustomCountdown() {
	const customTime = parseInt(document.getElementById("customCountdown").value);
	if (!isNaN(customTime) && customTime > 0) {
		setCountdown(customTime);
	} else {
		alert("Please enter a valid number of seconds");
	}
}

// Controla el botón de iniciar/pausar
function startPauseTimer() {
	const startPauseBtn = document.getElementById("startPauseBtn");

	if (isPaused) {
		// Si estaba pausado, reiniciamos el tiempo de inicio
		localStorage.setItem(`startTime_${currentSpaceId}`, new Date().getTime());
		pausedAt = null; // Reiniciamos la pausa
		localStorage.removeItem(`pausedAt_${currentSpaceId}`);

		startTimer(); // Iniciar el temporizador actual
		startPauseBtn.textContent = "Pausar";
		startPauseBtn.classList.remove("btn-primary", "btn-success");
		startPauseBtn.classList.add("btn-warning");
		isPaused = false;
	} else {
		// Al pausar, guardamos el tiempo actual
		pausedAt = new Date().getTime();
		localStorage.setItem(`pausedAt_${currentSpaceId}`, pausedAt);
		pauseTimer();
		startPauseBtn.textContent = "Continuar";
		startPauseBtn.classList.remove("btn-warning");
		startPauseBtn.classList.add("btn-primary");
		isPaused = true;
	}
	localStorage.setItem(`isPaused_${currentSpaceId}`, isPaused);
	localStorage.setItem(`timeElapsed_${currentSpaceId}`, timeElapsed); // Guardar el tiempo transcurrido
	if (!isStopwatch) {
		// Para countdown, guardar el tiempo restante exacto
		const remainingTime = countdownTime - (timeElapsed || elapsedTime);
		localStorage.setItem(`countdownTime_${currentSpaceId}`, remainingTime);
	}
}

// Inicia el temporizador
function startTimer() {
	// Evitar intervalos múltiples
	if (timerInterval) clearInterval(timerInterval);

	// Almacenar el ID de la tarjeta actual
	localStorage.setItem("currentSpaceId", currentSpaceId);

	timerInterval = setInterval(() => {
		if (currentSpaceId === localStorage.getItem("currentSpaceId")) {
			calculateAndDisplayTime();
		}
	}, 1000);
}

// Pausa el temporizador
function pauseTimer() {
	clearInterval(timerInterval);
	// Guardar el tiempo transcurrido en el momento de la pausa
	if (isStopwatch) {
		localStorage.setItem(`timeElapsed_${currentSpaceId}`, timeElapsed);
	} else {
		// Para countdown, guardar el tiempo restante exacto
		const remainingTime = countdownTime - timeElapsed;
		localStorage.setItem(`countdownTime_${currentSpaceId}`, remainingTime);
	}
}

// Reinicia el temporizador
function resetTimer() {
	clearInterval(timerInterval);
	localStorage.removeItem(`startTime_${currentSpaceId}`);
	localStorage.removeItem(`isPaused_${currentSpaceId}`);
	localStorage.removeItem(`isStopwatch_${currentSpaceId}`);
	localStorage.removeItem(`timeElapsed_${currentSpaceId}`);
	localStorage.removeItem(`pausedAt_${currentSpaceId}`);
	localStorage.removeItem(`countdownTime_${currentSpaceId}`);

	timeElapsed = 0;
	countdownTime = 0;
	document.getElementById("timer-display").textContent = "00:00:00";
	document.getElementById("startPauseBtn").textContent = "Empezar";
	document
		.getElementById("startPauseBtn")
		.classList.remove("btn-primary", "btn-warning");
	document.getElementById("startPauseBtn").classList.add("btn-success");
	isPaused = true;
	pausedAt = null;
}

// Calcula y muestra el tiempo actual
function calculateAndDisplayTime() {
	const startTime = parseInt(
		localStorage.getItem(`startTime_${currentSpaceId}`)
	);
	if (!startTime) return;

	const currentTime = new Date().getTime();
	let elapsedTime = Math.floor((currentTime - startTime) / 1000);

	if (isStopwatch) {
		// Mostrar el tiempo transcurrido para el cronómetro
		timeElapsed =
			parseInt(localStorage.getItem(`timeElapsed_${currentSpaceId}`)) +
			elapsedTime;
		document.getElementById("timer-display").textContent =
			formatTime(timeElapsed);
	} else {
		// Mostrar el tiempo restante para la cuenta regresiva
		let remainingTime = countdownTime - elapsedTime;
		if (remainingTime <= 0) {
			clearInterval(timerInterval);
			document.getElementById("timer-display").textContent = "00:00:00";
			alert("Countdown finished!");
			resetTimer();
		} else {
			timeElapsed = countdownTime - remainingTime; // Guardar el tiempo transcurrido correctamente
			document.getElementById("timer-display").textContent =
				formatTime(remainingTime);
		}
	}
}

// Actualiza el display al cargar
function updateDisplay() {
	if (isStopwatch) {
		const currentTimeElapsed =
			parseInt(localStorage.getItem(`timeElapsed_${currentSpaceId}`)) || 0;
		document.getElementById("timer-display").textContent =
			formatTime(currentTimeElapsed);
	} else {
		const remainingTime =
			parseInt(localStorage.getItem(`countdownTime_${currentSpaceId}`)) ||
			countdownTime;
		document.getElementById("timer-display").textContent =
			formatTime(remainingTime);
	}
}

// Formatea el tiempo en HH:MM:SS
function formatTime(seconds) {
	const hrs = Math.floor(seconds / 3600);
	const mins = Math.floor((seconds % 3600) / 60);
	const secs = seconds % 60;
	return `${hrs.toString().padStart(2, "0")}:${mins
		.toString()
		.padStart(2, "0")}:${secs.toString().padStart(2, "0")}`;
}
