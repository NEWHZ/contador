<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class AsignarTiempo extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        // Cargar el modelo que será utilizado para obtener los espacios activos
        $this->load->model('ModeloAsignarTiempo');
        $this->load->helper('url');
    }

    public function index()
    {
        // Obtener los espacios activos y sus tiempos
        $data['espacios'] = $this->ModeloAsignarTiempo->getActiveEspacios();

        // Cargar la vista con los datos de los espacios
        $this->load->view('contador', $data);  // La vista del contador que incluye el cronómetro
    }

    public function mostrarTablero()
    {
        // También obtén los datos de los espacios en la vista del tablero si es necesario
        $data['espacios'] = $this->ModeloAsignarTiempo->getActiveEspacios();

        // Cargar la vista del tablero con los datos de los espacios
        $this->load->view('Tablero', $data);  // Cargar la vista del tablero
    }
}
