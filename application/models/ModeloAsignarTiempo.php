<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ModeloAsignarTiempo extends CI_Model {

    public function __construct()
    {
        parent::__construct();
        $this->load->database();  // Cargar la base de datos
    }

    // Método para obtener todos los espacios de trabajo que están en estado 'activo'
    public function getActiveEspacios()
    {
        // Seleccionar los espacios de trabajo que estén en estado 'activo'
        $this->db->where('estado', 'activo');
        $query = $this->db->get('espacios_trabajo');
        return $query->result_array();  // Devolver los resultados como un array asociativo
    }
}
