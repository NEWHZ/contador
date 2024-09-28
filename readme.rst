# *Contador - Temporizador y Widget Meteorológico*

### *Descripción del Proyecto*
Este proyecto es una aplicación web que combina un temporizador/cronómetro funcional con un widget meteorológico que muestra el clima de la *ubicación actual* del usuario, utilizando la API de RapidAPI. Los usuarios pueden ver en tiempo real las condiciones climáticas, como la temperatura y humedad, mientras utilizan el temporizador para gestionar actividades. El temporizador también permite personalizar la cuenta regresiva, con alertas sonoras y cambios en el color de fondo al finalizar.

---

### *Requisitos*
Antes de comenzar, asegúrate de tener los siguientes requisitos instalados en tu sistema:

- *Apache*: 
  - Versión: Apache/2.4.52
  
- *PHP*: 
  - Versión: PHP 7.3.33-20
  
- *MySQL*: 
  - Versión: 8.0.39
  
- *CodeIgniter*: 
  - Versión: 3.x

---

### *Instalación del Proyecto*

#### *1. Instalación Local*
1. *Clonar el repositorio*:  
   Ejecuta el siguiente comando en tu terminal:
   bash
   git clone https://github.com/NEWHZ/contador
   
   
2. *Mover los archivos*:  
   Coloca los archivos clonados en el directorio de tu servidor local:
   - Para *XAMPP*: htdocs/.
   - Para *WAMP*: www/.

3. *Acceder a la aplicación*:  
   Abre un navegador web y accede a la siguiente URL:  
   
   http://localhost/contador/index.php
   

#### *2. Configuración de la Base de Datos*
1. *Crear la base de datos*:  
   Usa el script SQL incluido en el proyecto para crear la base de datos. El archivo se encuentra en:  
   
   contador_schema_with_db.sql
   

2. *Importar el esquema*:  
   Importa el archivo SQL en tu sistema de gestión de bases de datos para crear las tablas necesarias para el proyecto.

---

### *Widget Meteorológico*
El widget meteorológico se conecta a una API gratuita de RapidAPI utilizando la función fetch para mostrar el clima en *tiempo real de la ubicación actual* del usuario. Este widget proporciona información detallada sobre la temperatura, la humedad y las condiciones generales del clima, lo que permite al usuario estar informado sobre el entorno mientras gestiona su tiempo con el cronómetro.