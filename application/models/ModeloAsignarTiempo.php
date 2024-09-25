<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ModeloAsignarTiempo extends CI_Model {

    public function __construct()
    {
        parent::__construct();
        $this->load->database();  // Cargar la base de datos
    }

    // Método para obtener todos los espacios de trabajo que están en estado 'activo' y sus categorías asociadas
    public function getActiveEspacios()
    {
        // Seleccionar los espacios de trabajo que estén en estado 'activo' junto con su categoría
        $this->db->select('espacios_trabajo.*, categorias.nombre as categoria');
        $this->db->from('espacios_trabajo');
        $this->db->join('categorias', 'espacios_trabajo.categoria_id = categorias.id', 'left');  // Unir con la tabla de categorías
        $this->db->where('espacios_trabajo.estado', 'activo');  // Asegurarse de filtrar por espacios activos
        $query = $this->db->get();
        
        return $query->result_array();  // Devolver los resultados como un array asociativo
    }

    // Método para obtener todas las categorías
    public function getCategorias()
    {
        // Seleccionar todas las categorías
        $query = $this->db->get('categorias');
        return $query->result_array();  // Devolver los resultados como un array asociativo
    }
}
