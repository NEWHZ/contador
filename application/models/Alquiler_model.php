<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Alquiler_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    // Obtener el precio de la categoría del espacio
    public function obtenerPrecioCategoria($espacio_id) {
        $this->db->select('categorias.precio');
        $this->db->from('espacios_trabajo');
        $this->db->join('categorias', 'espacios_trabajo.categoria_id = categorias.id');
        $this->db->where('espacios_trabajo.id', $espacio_id);
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            return $query->row()->precio;
        }
        return 0;
    }

     // Guardar el registro del alquiler en historial_alquiler
     public function guardarAlquiler($data) {
        log_message('info', 'Intentando guardar los datos: ' . json_encode($data));
        return $this->db->insert('historial_alquiler', $data);
    }
    
  // Obtener el historial de alquileres con información del espacio y la categoría
  public function getHistorialAlquiler() {
    $this->db->select('historial_alquiler.*, espacios_trabajo.nombre as nombre_espacio, categorias.nombre as nombre_categoria');
    $this->db->from('historial_alquiler');
    $this->db->join('espacios_trabajo', 'historial_alquiler.espacio_id = espacios_trabajo.id');
    $this->db->join('categorias', 'espacios_trabajo.categoria_id = categorias.id', 'left'); // LEFT JOIN para mostrar espacios sin categoría
    $this->db->order_by('fecha_alquiler', 'DESC'); // Ordenar por fecha de alquiler descendente
    $query = $this->db->get();
    return $query->result_array();
}

    // Opcional: Agregar un método para filtrar registros, por ejemplo, por espacio de trabajo o fecha
    public function filtrarHistorial($filters) {
        $this->db->select('historial_alquiler.*, espacios_trabajo.nombre as nombre_espacio, categorias.nombre as nombre_categoria');
        $this->db->from('historial_alquiler');
        $this->db->join('espacios_trabajo', 'historial_alquiler.espacio_id = espacios_trabajo.id');
        $this->db->join('categorias', 'espacios_trabajo.categoria_id = categorias.id', 'left'); // LEFT JOIN para permitir espacios sin categoría
    
        // Aplicar filtros si se proporcionan
        if (!empty($filters['espacio_id'])) {
            $this->db->where('historial_alquiler.espacio_id', $filters['espacio_id']);
        }
        if (!empty($filters['categoria_id'])) {
            $this->db->where('espacios_trabajo.categoria_id', $filters['categoria_id']);
        }
        if (!empty($filters['fecha_desde'])) {
            $this->db->where('historial_alquiler.fecha_alquiler >=', date('Y-m-d 00:00:00', strtotime($filters['fecha_desde'])));
        }
        if (!empty($filters['fecha_hasta'])) {
            $this->db->where('historial_alquiler.fecha_alquiler <=', date('Y-m-d 23:59:59', strtotime($filters['fecha_hasta'])));
        }
    
        // Ordenar los resultados por fecha de alquiler
        $this->db->order_by('historial_alquiler.fecha_alquiler', 'DESC');
    
        $query = $this->db->get();
    
        // Verificar si hay resultados y retornarlos
        if ($query->num_rows() > 0) {
            return $query->result_array();
        } else {
            // Si no se encontraron resultados, devolver un array vacío
            return [];
        }
    }
    
    
    
}
