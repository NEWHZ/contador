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

	// Limpiar cualquier intervalo anterior para evitar múltiples intervalos
	clearInterval(timerInterval);

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

	// Si el cronómetro ya estaba en uso, no restablecer el tiempo
	if (timeElapsed === 0) {
		isPaused = true;
		timeElapsed = 0; // Solo si es un nuevo cronómetro
		localStorage.setItem(`isStopwatch_${currentSpaceId}`, "true");
		localStorage.setItem(`timeElapsed_${currentSpaceId}`, timeElapsed);
	}

	updateDisplay(); // Actualizar la pantalla
}

// Muestra las opciones del countdown con presets
function showCountdown() {
	document.getElementById("initial-content").style.display = "none"; // Ocultar elección inicial
	document.getElementById("timer-controls").style.display = "block"; // Mostrar controles
	document.getElementById("timer-type").textContent = "Countdown Active"; // Mostrar que countdown está activo
	isStopwatch = false; // Desactivar stopwatch

	// Mostrar el selector de horas y minutos para Countdown
	document.getElementById("countdown-settings").style.display = "block"; // Mostrar la selección de tiempo
	document.getElementById("startPauseBtn").style.display = "none"; // Ocultar botón hasta que se seleccione tiempo
	document.getElementById("resetBtn").style.display = "none"; // Ocultar reset
	document.getElementById("terminateBtn").style.display = "none"; // Ocultar terminar
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

function setCustomTime() {
	const hours = parseInt(document.getElementById("hours").value); // Obtener horas seleccionadas
	const minutes = parseInt(document.getElementById("minutes").value); // Obtener minutos seleccionados

	// Validar que se haya seleccionado un tiempo válido
	if (hours === 0 && minutes === 0) {
		Swal.fire({
			icon: "error",
			title: "Error",
			text: "Por favor selecciona un tiempo válido mayor que cero.",
		});
		return; // Si no se seleccionó un tiempo válido, detener la ejecución
	}

	// Convertir el tiempo a segundos
	const totalSeconds = hours * 3600 + minutes * 60;

	// Establecer el countdown
	setCountdown(totalSeconds);

	// Mostrar los botones de control una vez que se ha establecido el tiempo
	document.getElementById("startPauseBtn").style.display = "inline-block";
	document.getElementById("resetBtn").style.display = "inline-block";
	document.getElementById("terminateBtn").style.display = "inline-block";

	// Ocultar la selección de horas y minutos
	document.getElementById("countdown-settings").style.display = "none";
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

	// Guardar el estado actual en localStorage
	localStorage.setItem(`isPaused_${currentSpaceId}`, isPaused);
	localStorage.setItem(`timeElapsed_${currentSpaceId}`, timeElapsed);
}

// Inicia el temporizador
function startTimer() {
	// Limpiar cualquier intervalo existente antes de crear uno nuevo
	if (timerInterval) clearInterval(timerInterval);

	// Almacenar el ID de la tarjeta actual
	localStorage.setItem("currentSpaceId", currentSpaceId);

	// Crear un nuevo intervalo para actualizar el tiempo cada segundo
	timerInterval = setInterval(() => {
		if (currentSpaceId === localStorage.getItem("currentSpaceId")) {
			calculateAndDisplayTime(); // Actualizar el tiempo
		}
	}, 1000);
}

// Restablecer el modal cuando se cierra
$("#timerModal").on("hidden.bs.modal", function () {
	resetModal(); // Llamar a la función para resetear el modal
});

// Función para resetear la interfaz del modal
function resetModal() {
	document.getElementById("initial-content").style.display = "block"; // Mostrar la elección de temporizador
	document.getElementById("timer-controls").style.display = "none"; // Ocultar controles del temporizador
	document.getElementById("countdown-settings").style.display = "none"; // Ocultar selección de tiempo
	document.getElementById("timer-display").textContent = "00:00:00"; // Restablecer display de tiempo
}

// Función para reiniciar el temporizador
function resetTimer() {
	clearInterval(timerInterval); // Limpiar intervalos activos
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

	resetModal(); // Restablecer la interfaz del modal al reiniciar
}

// Intervalo para enviar datos al servidor
let syncInterval = setInterval(() => {
	if (!isPaused && currentSpaceId) {
		syncTimerData();
	}
}, 10000); // Sincronizar cada 10 segundos

function syncTimerData() {
	if (isStopwatch) return; // No sincronizar si es stopwatch

	const startTime = parseInt(
		localStorage.getItem(`startTime_${currentSpaceId}`)
	);
	const currentTime = new Date().getTime();
	const elapsedTime = Math.floor((currentTime - startTime) / 1000);
	const remainingTime = countdownTime - elapsedTime;

	// Solo registrar cuando el tiempo restante es exactamente 0 o menos
	if (remainingTime <= 0 && countdownTime > 0) {
		registrarAlquiler(currentSpaceId, countdownTime)
			.then((response) => {
				console.log("Sincronización exitosa con la BD:", response);
				showCountdownAlert(); // Mostrar alerta cuando el tiempo se acaba
				resetTimer();
			})
			.catch((error) => {
				console.error("Error al sincronizar con la BD:", error);
			});

		clearInterval(timerInterval); // Detener el temporizador y sincronización para evitar registros adicionales
	}
}

// Mostrar una alerta cuando el tiempo del countdown termina
function showCountdownAlert() {
	Swal.fire({
		icon: "info",
		title: "Countdown finalizado",
		text: "El tiempo del temporizador ha finalizado.",
		confirmButtonText: "Aceptar",
		timer: 2000, // Mostrar alerta por 2 segundos
		timerProgressBar: true,
		backdrop: true, // Esto asegura que el modal de la alerta se superponga a cualquier otra cosa
	});
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

			resetTimer(); // Reiniciar el temporizador cuando finalice el countdown
		} else {
			// Actualizar la visualización y guardar el tiempo restante correctamente
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
