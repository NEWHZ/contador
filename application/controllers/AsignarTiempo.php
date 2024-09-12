<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class AsignarTiempo extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        // Cargar el modelo que será utilizado para obtener los espacios activos
        $this->load->model('ModeloAsignarTiempo');
        $this->load->helper('url'); // Para usar base_url()
    }

    // Método para cargar la vista con los espacios activos
    public function index()
    {
        // Obtener los espacios de trabajo que están activos
        $data['espacios'] = $this->ModeloAsignarTiempo->getActiveEspacios();

        // Cargar la vista y pasar los datos de los espacios activos
        $this->load->view('contador', $data);
    }
}
