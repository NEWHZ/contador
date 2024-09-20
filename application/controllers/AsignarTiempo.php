<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class AsignarTiempo extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('ModeloAsignarTiempo');
        $this->load->helper('url'); // For using base_url()
    }

    public function index()
    {
        // Fetch active spaces from the model
        $data['espacios'] = $this->ModeloAsignarTiempo->getActiveEspacios();
        // Load the main view to assign time
        $this->load->view('contador', $data);
    }

    // New method to load the tablero view
    public function tablero()
    {
        // Fetch active spaces from the database
        $data['espacios'] = $this->ModeloAsignarTiempo->getActiveEspacios();
        // Load the tablero view with the spaces data
        $this->load->view('tablero', $data);
    }
    
}
