### Finalidad del proyecto
Este proyecto es una aplicación web que permite a los usuarios utilizar un temporizador y 
cronómetro interactivo con opciones de personalización. Los usuarios pueden ajustar el 
tiempo de la cuenta regresiva, cambiar el color de fondo al finalizar y activar una alerta 
sonora. La idea detrás del proyecto es crear una herramienta sencilla pero eficaz para tareas 
de seguimiento de tiempo, que puede ser utilizada en múltiples contextos, como estudio, 
trabajo o actividades físicas.


### Requisitos
Antes de comenzar, asegúrate de tener los siguientes requisitos instalados en tu sistema:

-Apache:
Versión: Apache/2.4.52 (Ubuntu)
Compilado: 2024-07-17T18:57:26

-PHP:
Versión: PHP 7.3.33-20+ubuntu22.04.1+deb.sury.org+1 (CLI)
Compilado: Aug 2 2024 16:18:50
Zend Engine: v3.3.33
Zend OPcache: v7.3.33-20+ubuntu22.04.1+deb.sury.org+1

-MySQL*
Versión: 8.0.39-0ubuntu0.22.04.1
Sistema operativo: Linux (Ubuntu)

-codeigniter 3

### Instalación del proyecto: 
--local
Clonar el repositorio: Ejecuta el siguiente comando en tu terminal:
git clone https://github.com/NEWHZ/contador
Mover los archivos: Coloca los archivos clonados en el directorio de tu servidor local. Para XAMPP, deberías moverlos a la carpeta htdocs/. Para WAMP, dentro de www/.
Acceder a la aplicación: Abre un navegador y accede a la siguiente URL: http://localhost/contador/index.php.

Crear la base de datos, y asignar el archivo database.php en Application/config 

--Entorno de AWS/EC2

Crear una instancia, elegir sistema operativo(este proyecto se uso ubuntu de 64bits x86, el tipo de instancia es t2.micro, usar la llave .pem, se hilito el trafico htttp y https, se uso almacenamiento de 8gb y gp3 volumen de raiz, se lanza instancia.)

Hayy que conectarse por medio de ssh desde una terminal(ssh -i "(la llave .pem)" ubuntu@(se colocala el DNS de la instancia))

luego se instala las versiones de apache, php y mysql ya mencionados.

Se crea la base de datos
moverse a /var/www/html
se clona el proyecto: git clone https://github.com/NEWHZ/contador

Asignar el archivo database.php en Application/config 

Cambiar la url en Application/config/config.php y colocar la ip 

### Características de Código Abierto
	Este proyecto es **de código abierto**, lo que significa que es libre para usar, modificar 	y distribuir. Cualquiera puede contribuir al desarrollo, mejorar las funcionalidades existentes o adaptar la aplicación según sus necesidades.

### Acceso al Código Fuente
	El código fuente de este proyecto está disponible en 
	[GitHub]https://github.com/NEWHZ/contador.git 

### Licencia
	Este proyecto está licenciado bajo la [Licencia MIT](LICENSE). Puedes consultar el 
	archivo de licencia para más detalles.

### Contribuciones
Las contribuciones son bienvenidas. Si deseas contribuir, por favor sigue estos pasos:

	Haz un fork del proyecto.
	Crea una nueva rama (git checkout -b feature/nueva-funcionalidad).
	Realiza tus cambios y haz commit (git commit -m 'Añadir nueva funcionalidad').
	Haz push a la rama (git push origin feature/nueva-funcionalidad).
	Abre un pull request.
	
	Contacto
	Para cualquier consulta o sugerencia, puedes contactar a cualquiera de los desarrolladores:
	crequenam@miumg.edu.gt
	aterrazav2@gmiumg.edu.gt
	nosoriom1@miumg.edu.gt
	acastellanosr3@miumg.edu.gt
