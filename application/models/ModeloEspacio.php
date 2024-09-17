<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ModeloEspacio extends CI_Model
{
    protected $table = 'espacios_trabajo';  // Nombre de la tabla

    public function __construct()
    {
        parent::__construct();
        $this->load->database();  // Cargar la base de datos
    }

    // Insertar un nuevo espacio de trabajo
    public function insertEspacio($data)
    {
        return $this->db->insert($this->table, $data);
    }

    // Actualizar un espacio de trabajo
    public function updateEspacio($id, $data)
    {
        // Se busca por el ID del espacio y luego se actualizan los datos
        $this->db->where('id', $id);
        return $this->db->update($this->table, $data);
    }

    // Obtener un espacio de trabajo por su ID
    public function getEspacioById($id)
    {
        return $this->db->get_where($this->table, ['id' => $id])->row_array();
    }

    // Obtener todos los espacios de trabajo
    public function getAllEspacios()
    {
        return $this->db->get($this->table)->result_array();
    }

    // Eliminar un espacio de trabajo
    public function deleteEspacio($id)
    {
        return $this->db->delete($this->table, ['id' => $id]);
    }
}
