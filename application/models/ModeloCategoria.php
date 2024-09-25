<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ModeloCategoria extends CI_Model
{
    protected $table = 'categorias';  // Nombre de la tabla

    public function __construct()
    {
        parent::__construct();
        $this->load->database();  // Cargar la base de datos
    }

    // Insertar una nueva categoría
    public function insertCategoria($data)
    {
        return $this->db->insert($this->table, $data);
    }

    // Actualizar una categoría
    public function updateCategoria($id, $data)
    {
        // Se busca por el ID de la categoría y luego se actualizan los datos
        $this->db->where('id', $id);
        return $this->db->update($this->table, $data);
    }

    // Obtener una categoría por su ID
    public function getCategoriaById($id)
    {
        return $this->db->get_where($this->table, ['id' => $id, 'borrado_logico' => 0])->row_array();
    }

    // Obtener todas las categorías que no están eliminadas
    public function getAllCategorias()
    {
        return $this->db->get_where($this->table, ['borrado_logico' => 0])->result_array();
    }

    // Borrado lógico de una categoría
    public function deleteCategoria($id)
    {
        $this->db->where('id', $id);
        return $this->db->update($this->table, ['borrado_logico' => 1]);
    }

    // Obtener todas las categorías con su límite de tiempo
    public function getCategorias()
    {
        $this->db->select('id, nombre, precio, tiempo_limite');  // Asegúrate de incluir 'tiempo_limite'
        $query = $this->db->get('categorias');
        return $query->result_array();
    }
}
