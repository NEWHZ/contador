<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
| 
| Typical URL pattern:
|	example.com/class/method/id/
| 
| Reserved Routes:
|	$route['default_controller'] = 'welcome';
|	$route['404_override'] = 'errors/page_missing';
|	$route['translate_uri_dashes'] = FALSE;
*/

// El controlador predeterminado que se carga si no se especifica nada
$route['default_controller'] = 'AuthController/login';

// Ruta en caso de error 404 (página no encontrada)
$route['404_override'] = '';

// Deshabilitar la traducción automática de guiones a guiones bajos en nombres de clases y métodos
$route['translate_uri_dashes'] = FALSE;

/*
| -------------------------------------------------------------------------
| RUTAS CRUD para el controlador Espacios
| -------------------------------------------------------------------------
|
| Estas rutas están asignadas para el controlador Espacios que maneja 
| las operaciones CRUD para los espacios de trabajo.
|
*/
// Rutas para el controlador Espacios (CRUD)
$route['espacios'] = 'Espacios/index';       // Mostrar el tablero de espacios
$route['espacios/store'] = 'Espacios/store'; // Guardar nuevo espacio (POST)
$route['espacios/edit/(:num)'] = 'Espacios/edit/$1'; // Mostrar formulario de edición
$route['espacios/update/(:num)'] = 'Espacios/update/$1'; // Actualizar espacio (POST)
$route['espacios/delete/(:num)'] = 'Espacios/delete/$1'; // Eliminar espacio (GET o POST)

// Ruta para asignar tiempo (controlador AsignarTiempo)
$route['asignar-tiempo'] = 'AsignarTiempo/index';




$route['categorias'] = 'categorias/index';
$route['categorias/store'] = 'categorias/store';
$route['categorias/edit/(:num)'] = 'categorias/edit/$1';
$route['categorias/update/(:num)'] = 'categorias/update/$1';
$route['categorias/delete/(:num)'] = 'categorias/delete/$1';



$route['alquiler'] = 'alquiler/index';
$route['alquiler/registrarAlquiler'] = 'alquiler/registrarAlquiler';
$route['alquiler/historial'] = 'alquiler/historial';
$route['alquiler/filtrarHistorial'] = 'alquiler/filtrarHistorial';
$route['tablero'] = 'AsignarTiempo/mostrarTablero';  // Esto llamará al método que maneja el tablero

// Rutas de autenticación
$route['auth/register'] = 'AuthController/register';
$route['auth/process_register'] = 'AuthController/process_register';
$route['auth/login'] = 'AuthController/login';
$route['auth/process_login'] = 'AuthController/process_login';
$route['auth/logout'] = 'AuthController/logout';

// Rutas del panel de administración

$route['usuarios'] = 'Usuarios/index';  // Lista de usuarios
$route['usuarios/pendientes'] = 'Usuarios/pendientes';  // Usuarios pendientes
$route['usuarios/aprobar/(:num)'] = 'Usuarios/aprobar/$1';  // Aprobar usuario
$route['usuarios/edit/(:num)'] = 'Usuarios/edit/$1';  // Editar usuario
$route['usuarios/update/(:num)'] = 'Usuarios/update/$1';  // Actualizar usuario
$route['usuarios/delete/(:num)'] = 'Usuarios/delete/$1';  // Eliminar usuario
