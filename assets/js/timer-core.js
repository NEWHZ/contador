let timerInterval;
let isStopwatch = false;
let timeElapsed = 0;
let countdownTime = 0; // El valor predeterminado eliminado
let isPaused = true;
let currentSpaceId = null;
let pausedAt = null;

function openTimerModal(spaceId) {
	currentSpaceId = spaceId;

	// Limpiar cualquier temporizador previo al abrir el modal
	if (timerInterval) {
		clearInterval(timerInterval); // Limpiamos cualquier intervalo previo
	}

	// Cargar valores almacenados en localStorage
	const storedStartTime =
		parseInt(localStorage.getItem(`startTime_${spaceId}`)) || 0;
	const storedPausedState = localStorage.getItem(`isPaused_${spaceId}`);
	const storedIsStopwatch =
		localStorage.getItem(`isStopwatch_${spaceId}`) === "true";
	const storedCountdownTime =
		parseInt(localStorage.getItem(`countdownTime_${spaceId}`)) || 0;

	document.getElementById("initial-content").style.display = "block";
	document.getElementById("timer-controls").style.display = "none";

	// Eliminar cualquier preset duplicado
	const presetContainer = document.getElementById("preset-container");
	if (presetContainer) presetContainer.remove();

	// Si hay un temporizador guardado, mostrar los controles del temporizador
	if (storedStartTime > 0) {
		countdownTime = storedCountdownTime;
		pausedAt =
			storedPausedState === "true"
				? parseInt(localStorage.getItem(`pausedAt_${spaceId}`))
				: null;
		isPaused = storedPausedState === "true";
		isStopwatch = storedIsStopwatch;

		document.getElementById("initial-content").style.display = "none";
		document.getElementById("timer-controls").style.display = "block";
		document.getElementById("timer-type").textContent = isStopwatch
			? "StopWatch Active"
			: "Countdown Active";

		// Actualizar el display con el tiempo actual
		updateDisplay();

		// Si no está pausado, continuar con el temporizador
		if (!isPaused) {
			startTimer(); // Iniciar el temporizador
			document.getElementById("startPauseBtn").textContent = "Pausar";
			document
				.getElementById("startPauseBtn")
				.classList.remove("btn-success", "btn-primary");
			document.getElementById("startPauseBtn").classList.add("btn-warning");
		} else {
			document.getElementById("startPauseBtn").textContent = "Continuar";
			document.getElementById("startPauseBtn").classList.remove("btn-warning");
			document.getElementById("startPauseBtn").classList.add("btn-primary");
		}
	} else {
		resetTimer();
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

	if (!document.getElementById("preset-container")) {
		const presetContainer = document.createElement("div");
		presetContainer.id = "preset-container";
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

function setCountdown(seconds) {
	countdownTime = seconds;
	isPaused = true;
	localStorage.setItem(`isStopwatch_${currentSpaceId}`, "false");
	localStorage.setItem(`countdownTime_${currentSpaceId}`, countdownTime);
	updateDisplay();
}

function setCustomCountdown() {
	const customTime = parseInt(document.getElementById("customCountdown").value);
	if (!isNaN(customTime) && customTime > 0) {
		setCountdown(customTime);
	} else {
		alert("Please enter a valid number of seconds");
	}
}

function startPauseTimer() {
	const startPauseBtn = document.getElementById("startPauseBtn");

	if (isPaused) {
		const currentTime = new Date().getTime();

		// Si estaba pausado, actualizar startTime restando el tiempo de pausa
		if (pausedAt) {
			const pauseDuration = currentTime - pausedAt;
			localStorage.setItem(
				`startTime_${currentSpaceId}`,
				parseInt(localStorage.getItem(`startTime_${currentSpaceId}`)) +
					pauseDuration
			);
		} else {
			localStorage.setItem(`startTime_${currentSpaceId}`, currentTime);
		}

		pausedAt = null;
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

function startTimer() {
	if (timerInterval) clearInterval(timerInterval); // Evita múltiples intervalos

	localStorage.setItem("currentSpaceId", currentSpaceId);

	timerInterval = setInterval(() => {
		if (currentSpaceId === localStorage.getItem("currentSpaceId")) {
			calculateAndDisplayTime();
		}
	}, 1000);
}

// Intervalo para enviar datos al servidor
let syncInterval = setInterval(() => {
	if (!isPaused && currentSpaceId) {
		syncTimerData();
	}
}, 10000); // Sincronizar cada 10 segundos

function syncTimerData() {
	if (isStopwatch) return; // No sincronizar si es stopwatch
	// Solo registrar si el countdown ha terminado
	const tiempoEnSegundos =
		countdownTime -
		Math.floor(
			(new Date().getTime() -
				parseInt(localStorage.getItem(`startTime_${currentSpaceId}`))) /
				1000
		);

	if (tiempoEnSegundos > 0) {
		registrarAlquiler(currentSpaceId, tiempoEnSegundos)
			.then((response) => {
				console.log("Sincronización exitosa con la BD:", response);
			})
			.catch((error) => {
				console.error("Error al sincronizar con la BD:", error);
			});
	}
}

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

function resetTimer() {
	clearInterval(timerInterval);
	localStorage.removeItem(`startTime_${currentSpaceId}`);
	localStorage.removeItem(`isPaused_${currentSpaceId}`);
	localStorage.removeItem(`isStopwatch_${currentSpaceId}`);
	localStorage.removeItem(`timeElapsed_${currentSpaceId}`);
	localStorage.removeItem(`pausedAt_${currentSpaceId}`);
	localStorage.removeItem(`countdownTime_${currentSpaceId}`);

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

function calculateAndDisplayTime() {
	const startTime =
		parseInt(localStorage.getItem(`startTime_${currentSpaceId}`)) || 0;
	const currentTime = new Date().getTime();
	const elapsedTime = Math.floor((currentTime - startTime) / 1000);

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
	const startTime =
		parseInt(localStorage.getItem(`startTime_${currentSpaceId}`)) || 0;
	const currentTime = new Date().getTime();
	const elapsedTime = Math.floor((currentTime - startTime) / 1000);

	if (isStopwatch) {
		document.getElementById("timer-display").textContent =
			formatTime(elapsedTime);
	} else {
		const remainingTime =
			parseInt(localStorage.getItem(`countdownTime_${currentSpaceId}`)) ||
			countdownTime;
		document.getElementById("timer-display").textContent =
			formatTime(remainingTime);
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

function terminarStopwatch() {
	// Detener el temporizador
	clearInterval(timerInterval);

	// Obtener el tiempo total en segundos
	const tiempoEnSegundos = timeElapsed;

	// Verificar si el tiempo es mayor a 0 antes de registrar
	if (tiempoEnSegundos <= 0) {
		Swal.fire({
			icon: "error",
			title: "Error",
			text: "No se puede registrar un alquiler con tiempo cero.",
		});
		return;
	}

	// Llamar a la función para registrar el alquiler
	registrarAlquiler(currentSpaceId, tiempoEnSegundos)
		.then((response) => {
			Swal.fire({
				icon: "success",
				title: "Alquiler registrado",
				text: "El tiempo ha sido registrado con éxito.",
				confirmButtonText: "Aceptar",
			}).then(() => {
				// Cerrar el modal
				$("#timerModal").modal("hide");
				// Resetear el temporizador
				resetTimer();
			});
		})
		.catch((error) => {
			Swal.fire({
				icon: "error",
				title: "Error",
				text: "Hubo un problema al registrar el alquiler. Por favor, inténtalo de nuevo.",
			});
		});
}

// Función para registrar el alquiler en la base de datos
function registrarAlquiler(espacioId, tiempoEnSegundos) {
	return new Promise((resolve, reject) => {
		// Evitar enviar datos con tiempo 0
		if (tiempoEnSegundos <= 0) {
			reject("No se puede registrar un alquiler con tiempo cero.");
			return;
		}

		// Convertir el tiempo a horas
		const horas = tiempoEnSegundos / 3600;

		console.log("Datos enviados:", { espacioId, tiempoEnSegundos, horas });

		$.ajax({
			url: baseURL + "alquiler/registrarAlquiler", // Usar la URL base definida
			type: "POST",
			data: {
				espacio_id: espacioId,
				tiempo_uso: horas,
				sync: true, // Indicar que esta es una sincronización periódica
			},
			success: function (response) {
				console.log("Respuesta del servidor:", response);

				try {
					const result = JSON.parse(response);
					if (result.status === "success") {
						resolve(result);
					} else {
						reject(result.message);
					}
				} catch (e) {
					console.error("Error al procesar la respuesta:", e);
					reject("Hubo un problema al registrar el alquiler.");
				}
			},
			error: function (error) {
				console.error("Error al registrar el alquiler:", error);
				reject("Error al conectar con el servidor.");
			},
		});
	});
}

// Manejo de eventos de navegación
window.addEventListener("beforeunload", () => {
	if (currentSpaceId) {
		syncTimerData(); // Enviar una actualización final al servidor
	}
});

document.addEventListener("visibilitychange", () => {
	if (document.visibilityState === "hidden" && currentSpaceId) {
		syncTimerData(); // Enviar datos cuando la pestaña se oculta
	}
});
