let timerInterval;
let isStopwatch = false;
let timeElapsed = 0;
let countdownTime = 0;
let isPaused = true;
let currentSpaceId = null;
let pausedAt = null;

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
		Swal.fire({
			icon: "error",
			title: "Error",
			text: "Please enter a valid number of seconds",
		});
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
		const remainingTime =
			countdownTime -
			Math.floor(
				(new Date().getTime() -
					parseInt(localStorage.getItem(`startTime_${currentSpaceId}`))) /
					1000
			);
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

// Intervalo para enviar datos al servidor
let syncInterval = setInterval(() => {
	if (!isPaused && currentSpaceId) {
		syncTimerData();
	}
}, 10000); // Sincronizar cada 10 segundos

function syncTimerData() {
	// Verificar si ya se ha sincronizado para evitar duplicados
	const alreadySynced = localStorage.getItem(`isSynced_${currentSpaceId}`);
	if (alreadySynced === "true") {
		return; // Si ya se ha sincronizado, no enviar nuevamente
	}

	const tiempoEnSegundos = isStopwatch
		? timeElapsed
		: countdownTime -
		  Math.floor(
				(new Date().getTime() -
					parseInt(localStorage.getItem(`startTime_${currentSpaceId}`))) /
					1000
		  );

	if (tiempoEnSegundos > 0) {
		registrarAlquiler(currentSpaceId, tiempoEnSegundos)
			.then((response) => {
				console.log("Sincronización exitosa con la BD:", response);
				// Marcar como sincronizado
				localStorage.setItem(`isSynced_${currentSpaceId}`, "true");
			})
			.catch((error) => {
				console.error("Error al sincronizar con la BD:", error);
			});
	}
}

// Pausa el temporizador
function pauseTimer() {
	clearInterval(timerInterval);
	// Guardar el tiempo transcurrido en el momento de la pausa
	if (isStopwatch) {
		localStorage.setItem(`timeElapsed_${currentSpaceId}`, timeElapsed);
	} else {
		// Para countdown, guardar el tiempo restante exacto
		const remainingTime =
			countdownTime -
			Math.floor(
				(new Date().getTime() -
					parseInt(localStorage.getItem(`startTime_${currentSpaceId}`))) /
					1000
			);
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
	localStorage.removeItem(`isSynced_${currentSpaceId}`); // Limpiar la bandera de sincronización

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

			// Registrar el tiempo solo si es mayor a cero
			if (countdownTime > 0) {
				registrarAlquiler(currentSpaceId, countdownTime) // Usar el tiempo total del countdown
					.then(() => {
						// Mostrar alerta y cerrar el modal automáticamente
						Swal.fire({
							icon: "info",
							title: "Countdown finalizado",
							text: "El tiempo del temporizador ha finalizado.",
							confirmButtonText: "Aceptar",
							timer: 2000, // Cerrar la alerta después de 2 segundos
							timerProgressBar: true,
						}).then(() => {
							// Cerrar el modal si está abierto
							$("#timerModal").modal("hide");
						});
					})
					.catch((error) => {
						console.error("Error al registrar el alquiler:", error);
					});
			}

			resetTimer();
		} else {
			// Guardar el tiempo restante correctamente
			localStorage.setItem(`countdownTime_${currentSpaceId}`, remainingTime);
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
			// Marcar como sincronizado
			localStorage.setItem(`isSynced_${currentSpaceId}`, "true");

			// Mostrar alerta de SweetAlert2
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
