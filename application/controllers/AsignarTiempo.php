<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class AsignarTiempo extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('ModeloAsignarTiempo');
        $this->load->helper('url');
    }

    public function index()
    {
        // Obtener los espacios activos y sus categorías
        $data['espacios'] = $this->ModeloAsignarTiempo->getActiveEspacios();
        $data['categorias'] = $this->ModeloAsignarTiempo->getCategorias();  // Añadir las categorías a los datos

        // Cargar la vista con los datos de los espacios y las categorías
        $this->load->view('contador', $data);
    }

    public function mostrarTablero()
    {
        // Obtener los espacios activos y las categorías para la vista Tablero
        $data['espacios'] = $this->ModeloAsignarTiempo->getActiveEspacios();
        $data['categorias'] = $this->ModeloAsignarTiempo->getCategorias();  // Asegurarse de obtener también las categorías

        // Cargar la vista del Tablero con los datos de los espacios y las categorías
        $this->load->view('Tablero', $data);
    }
}
