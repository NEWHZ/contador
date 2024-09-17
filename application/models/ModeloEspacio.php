<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ModeloEspacio extends CI_Model {

    // Obtener todos los espacios de trabajo (solo activos)
    public function getAllEspacios()
    {
        $this->db->where('borrado_logico !=', '1'); // Excluir los espacios inactivos (borrados lógicamente)
        $query = $this->db->get('espacios_trabajo');
        return $query->result_array();
    }

    // Insertar un nuevo espacio de trabajo
    public function insertEspacio($data)
    {
        return $this->db->insert('espacios_trabajo', $data);
    }

    // Actualizar un espacio de trabajo existente
    public function updateEspacio($id, $data)
    {
        $this->db->where('id', $id);
        return $this->db->update('espacios_trabajo', $data);
    }

    // Obtener un espacio de trabajo por su ID
    public function getEspacioById($id)
    {
        $query = $this->db->get_where('espacios_trabajo', ['id' => $id]);
        return $query->row_array();
    }

    // Borrado lógico de un espacio de trabajo
    public function deleteEspacio($id)
    {
        $this->db->where('id', $id);
        return $this->db->update('espacios_trabajo', ['estado' => 'inactivo']);
    }
}
