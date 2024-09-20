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
        // Insertar espacio de trabajo con borrado_logico en 0 por defecto
        $data['borrado_logico'] = 0;
        return $this->db->insert($this->table, $data);
    }

    // Actualizar un espacio de trabajo
    public function updateEspacio($id, $data)
    {
        // Actualizar el espacio de trabajo por su ID
        $this->db->where('id', $id);
        return $this->db->update($this->table, $data);
    }

    // Obtener un espacio de trabajo por su ID
    public function getEspacioById($id)
    {
        // Retornar los datos del espacio de trabajo, incluso si ha sido borrado lógicamente
        return $this->db->get_where($this->table, ['id' => $id])->row_array();
    }

    // Obtener todos los espacios de trabajo que no han sido eliminados lógicamente
    public function getAllEspacios()
    {
        $this->db->select('espacios_trabajo.*, categorias.nombre as nombre_categoria');
        $this->db->from($this->table);
        $this->db->join('categorias', 'espacios_trabajo.categoria_id = categorias.id', 'left');
        $this->db->where('espacios_trabajo.borrado_logico', 0);  // Solo incluir espacios no eliminados
        return $this->db->get()->result_array();
    }

    // Borrado lógico de un espacio de trabajo
    public function deleteEspacio($id)
    {
        // Borrado lógico: cambiar estado a 'inactivo' y marcar como borrado
        $data = [
            'estado' => 'inactivo',
            'borrado_logico' => 1
        ];

        $this->db->where('id', $id);
        return $this->db->update($this->table, $data);
    }
}
