<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$autoload['packages'] = array();

// Cargar la librería de base de datos y sesión automáticamente
$autoload['libraries'] = array('database', 'session');

// No se están usando drivers en este caso
$autoload['drivers'] = array();

// Cargar el helper de URL automáticamente para usar base_url()
$autoload['helper'] = array('url');

// No se están cargando configuraciones adicionales
$autoload['config'] = array();

// No se están cargando idiomas adicionales
$autoload['language'] = array();

// No se están cargando modelos de forma automática, pueden cargarse manualmente en controladores
$autoload['model'] = array();
