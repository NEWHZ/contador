<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />

<div id="weather-widget" class="position-fixed bottom-0 end-0 m-3">
    <button class="btn btn-primary rounded-circle p-2" id="weather-button" style="width: 50px; height: 50px; display: flex; align-items: center;">
        <span id="weather-icon" class="fs-4">☀️</span>
    </button>
</div>

<div id="weather-info" class="position-fixed p-4 rounded shadow-lg d-none animate__animated" 
     style="width: 280px; background: linear-gradient(135deg, #2e8bc0, #145374); color: white; bottom: 0px; right: 1rem; margin-bottom: 4.5rem;">
    <h5 class="fw-bold">Clima Actual</h5>
    <p id="loader">Cargando...</p>
    <p id="temperature" class="d-none">🌡️ Temperatura: --°C</p>
    <p id="condition" class="d-none">🌤️ Condiciones: --</p>
    <p id="humidity" class="d-none">💧 Humedad: --%</p>
    <p id="wind-speed" class="d-none">💨 Viento: -- km/h</p>
    <p id="sunrise-sunset" class="d-none">🌅 Amanecer: -- | 🌇 Atardecer: --</p>
</div>


<script>

  // Obtener referencias a los elementos
const weatherButton = document.getElementById('weather-button');
const weatherInfo = document.getElementById('weather-info');
const weatherIcon = document.getElementById('weather-icon');
const loader = document.getElementById('loader');
const elements = {
    temperature: document.getElementById('temperature'),
    condition: document.getElementById('condition'),
    humidity: document.getElementById('humidity'),
    windSpeed: document.getElementById('wind-speed'),
    sunriseSunset: document.getElementById('sunrise-sunset'),
};

// Mostrar/ocultar el contenedor y cargar datos al hacer clic
weatherButton.addEventListener('click', () => {
    if (weatherInfo.classList.contains('d-none')) {
        // Mostrar el contenedor con animación
        weatherInfo.classList.remove('d-none', 'animate__fadeOut');
        weatherInfo.classList.add('animate__fadeIn');

        // Cambiar el ícono a "X"
        weatherIcon.textContent = '❌';

        // Cargar datos del clima
        fetchWeather();
    } else {
        // Ocultar el contenedor con animación
        weatherInfo.classList.remove('animate__fadeIn');
        weatherInfo.classList.add('animate__fadeOut');

        // Cambiar el ícono de nuevo al clima después de que termine la animación
        weatherInfo.addEventListener('animationend', () => {
            if (weatherInfo.classList.contains('animate__fadeOut')) {
                weatherInfo.classList.add('d-none');
                weatherIcon.textContent = '☀️';
            }
        }, { once: true });
    }
});

// Función para obtener información del clima
async function fetchWeather() {
    const url = 'https://yahoo-weather5.p.rapidapi.com/weather?location=Guatemala%20City&format=json&u=f';
    const options = {
        method: 'GET',
        headers: {
            'x-rapidapi-key': 'aae5c5e79emsh09d23860b46c9e9p108134jsn2d7940392e7d',
            'x-rapidapi-host': 'yahoo-weather5.p.rapidapi.com',
        },
    };

    try {
        // Mostrar el loader y ocultar los datos
        loader.style.display = 'block';
        Object.values(elements).forEach(el => el.classList.add('d-none'));

        const response = await fetch(url, options);
        const result = await response.json();

        // Extraer la información relevante
        const data = {
            temperature: `🌡️ Temperatura: ${result.current_observation.condition.temperature}°F`,
            condition: `🌤️ Condiciones: ${result.current_observation.condition.text}`,
            humidity: `💧 Humedad: ${result.current_observation.atmosphere.humidity}%`,
            windSpeed: `💨 Viento: ${result.current_observation.wind.speed} km/h`,
            sunriseSunset: `🌅 Amanecer: ${result.current_observation.astronomy.sunrise} | 🌇 Atardecer: ${result.current_observation.astronomy.sunset}`,
        };

        // Actualizar los elementos con los datos
        for (let key in data) {
            elements[key].textContent = data[key];
            elements[key].classList.remove('d-none');
        }

        // Ocultar el loader
        loader.style.display = 'none';
    } catch (error) {
        console.error(error);
        loader.textContent = 'Error al cargar los datos';
    }
}

</script>
