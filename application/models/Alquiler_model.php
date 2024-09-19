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
        // Log para verificar los datos que se intentan guardar
        log_message('info', 'Intentando guardar los datos: ' . json_encode($data));
        
        return $this->db->insert('historial_alquiler', $data);
    }
    

    // Obtener el historial de alquileres con información del espacio
    public function getHistorialAlquiler() {
        $this->db->select('historial_alquiler.*, espacios_trabajo.nombre as nombre_espacio');
        $this->db->from('historial_alquiler');
        $this->db->join('espacios_trabajo', 'historial_alquiler.espacio_id = espacios_trabajo.id');
        $this->db->order_by('fecha_alquiler', 'DESC'); // Ordenar por fecha de alquiler descendente
        $query = $this->db->get();
        return $query->result_array();
    }

    // Opcional: Agregar un método para filtrar registros, por ejemplo, por espacio de trabajo o fecha
    public function filtrarHistorial($filters) {
        $this->db->select('historial_alquiler.*, espacios_trabajo.nombre as nombre_espacio');
        $this->db->from('historial_alquiler');
        $this->db->join('espacios_trabajo', 'historial_alquiler.espacio_id = espacios_trabajo.id');

        // Aplicar filtros si se proporcionan
        if (!empty($filters['espacio_id'])) {
            $this->db->where('historial_alquiler.espacio_id', $filters['espacio_id']);
        }
        if (!empty($filters['fecha_desde'])) {
            $this->db->where('historial_alquiler.fecha_alquiler >=', $filters['fecha_desde']);
        }
        if (!empty($filters['fecha_hasta'])) {
            $this->db->where('historial_alquiler.fecha_alquiler <=', $filters['fecha_hasta']);
        }

        $this->db->order_by('fecha_alquiler', 'DESC');
        $query = $this->db->get();
        return $query->result_array();
    }
}
