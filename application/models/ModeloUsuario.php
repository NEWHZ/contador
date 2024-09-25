<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ModeloUsuario extends CI_Model {

    protected $table = 'users'; // Nombre de la tabla de usuarios

    public function __construct() {
        $this->load->database();  // Cargar la base de datos
    }

    // Obtener todos los usuarios
    public function getAllUsuarios() {
        return $this->db->get($this->table)->result_array();
    }

    // Obtener usuarios pendientes (status = 0)
    public function getUsuariosPendientes() {
        return $this->db->get_where($this->table, ['status' => 0])->result_array();
    }

    // Aprobar un usuario (cambiar su estado a activo)
    public function aprobarUsuario($id) {
        $this->db->where('id', $id);
        return $this->db->update($this->table, ['status' => 1]);
    }

    // Obtener un usuario por su ID
    public function getUsuarioById($id) {
        return $this->db->get_where($this->table, ['id' => $id])->row_array();
    }

    // Actualizar un usuario
    public function updateUsuario($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update($this->table, $data);
    }

    // Eliminar un usuario
    public function deleteUsuario($id) {
        $this->db->where('id', $id);
        return $this->db->delete($this->table);
    }
}
