let timerInterval;
let isStopwatch = false;
let timeElapsed = 0;
let countdownTime = 300; // 5 minutos por defecto
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

	if (storedStartTime !== null) {
		timeElapsed = parseInt(storedTimeElapsed) || 0;
		pausedAt = storedPausedAt ? parseInt(storedPausedAt) : null;
		isPaused = storedPausedState === "true";
		isStopwatch = storedIsStopwatch === "true";

		document.getElementById("initial-content").style.display = "none";
		document.getElementById("timer-controls").style.display = "block";
		document.getElementById("timer-type").textContent = isStopwatch
			? "StopWatch Active"
			: "Countdown Active";

		// Mostrar el tiempo actual sin esperar a que el usuario presione continuar
		calculateAndDisplayTime();

		// Si no está pausado, continuar calculando el tiempo
		if (!isPaused) {
			startTimer();
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
		document.getElementById("initial-content").style.display = "block";
		document.getElementById("timer-controls").style.display = "none";
	}
}

function showStopwatch() {
	document.getElementById("initial-content").style.display = "none";
	document.getElementById("timer-controls").style.display = "block";
	document.getElementById("timer-type").textContent = "StopWatch Active";
	isStopwatch = true;
	isPaused = true;
	localStorage.setItem(`isStopwatch_${currentSpaceId}`, "true");
}

function showCountdown() {
	document.getElementById("initial-content").style.display = "none";
	document.getElementById("timer-controls").style.display = "block";
	document.getElementById("timer-type").textContent = "Countdown Active";
	isStopwatch = false;
	timeElapsed = countdownTime;
	isPaused = true;
	localStorage.setItem(`isStopwatch_${currentSpaceId}`, "false");
}

function startPauseTimer() {
	const startPauseBtn = document.getElementById("startPauseBtn");

	if (isPaused) {
		// Si estaba pausado, calculamos el nuevo tiempo de inicio restando el tiempo ya transcurrido
		const currentTime = new Date().getTime();
		if (pausedAt) {
			const pauseDuration = currentTime - pausedAt; // Tiempo que el temporizador estuvo en pausa
			const storedStartTime = parseInt(
				localStorage.getItem(`startTime_${currentSpaceId}`)
			);
			localStorage.setItem(
				`startTime_${currentSpaceId}`,
				storedStartTime + pauseDuration
			); // Ajustamos la hora de inicio
		} else {
			localStorage.setItem(`startTime_${currentSpaceId}`, currentTime);
		}

		pausedAt = null; // Reiniciamos la pausa
		localStorage.removeItem(`pausedAt_${currentSpaceId}`);

		startTimer();
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
}

function startTimer() {
	if (timerInterval) clearInterval(timerInterval);

	timerInterval = setInterval(() => {
		calculateAndDisplayTime();
	}, 1000);
}

function pauseTimer() {
	clearInterval(timerInterval);
}

function resetTimer() {
	clearInterval(timerInterval);
	localStorage.removeItem(`startTime_${currentSpaceId}`);
	localStorage.removeItem(`isPaused_${currentSpaceId}`);
	localStorage.removeItem(`isStopwatch_${currentSpaceId}`);
	localStorage.removeItem(`timeElapsed_${currentSpaceId}`);
	localStorage.removeItem(`pausedAt_${currentSpaceId}`);

	timeElapsed = isStopwatch ? 0 : countdownTime;
	document.getElementById("timer-display").textContent = isStopwatch
		? "00:00:00"
		: formatTime(countdownTime);
	document.getElementById("startPauseBtn").textContent = "Empezar";
	document
		.getElementById("startPauseBtn")
		.classList.remove("btn-primary", "btn-warning");
	document.getElementById("startPauseBtn").classList.add("btn-success");
	isPaused = true;
	pausedAt = null;
}

function calculateAndDisplayTime() {
	const startTime = localStorage.getItem(`startTime_${currentSpaceId}`);
	if (!startTime) return;

	const currentTime = new Date().getTime();
	let elapsedTime = Math.floor((currentTime - startTime) / 1000); // Tiempo en segundos

	if (isStopwatch) {
		// Mostrar el tiempo transcurrido para el cronómetro
		timeElapsed = elapsedTime;
		document.getElementById("timer-display").textContent =
			formatTime(timeElapsed);
	} else {
		// Mostrar el tiempo restante para la cuenta regresiva
		let remainingTime = countdownTime - elapsedTime;
		timeElapsed = remainingTime;
		if (remainingTime <= 0) {
			clearInterval(timerInterval);
			document.getElementById("timer-display").textContent = "00:00:00";
			alert("Countdown finished!");
			resetTimer();
		} else {
			document.getElementById("timer-display").textContent =
				formatTime(remainingTime);
		}
	}
}

function formatTime(seconds) {
	const hrs = Math.floor(seconds / 3600);
	const mins = Math.floor((seconds % 3600) / 60);
	const secs = seconds % 60;
	return `${hrs.toString().padStart(2, "0")}:${mins
		.toString()
		.padStart(2, "0")}:${secs.toString().padStart(2, "0")}`;
}
