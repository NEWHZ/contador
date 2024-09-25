<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model {

    protected $table = 'users';  // Nombre de la tabla

    public function __construct() {
        parent::__construct();
        $this->load->database();  // Cargar la base de datos
    }

    // Crear nuevo usuario
    public function create_user($data) {
        return $this->db->insert($this->table, $data);  // Insertar los datos en la base de datos
    }

    // Obtener un usuario por su nombre de usuario
    public function get_user_by_username($username) {
        return $this->db->get_where($this->table, ['username' => $username])->row_array();  // Retorna un array con los datos del usuario
    }

    // Obtener un usuario por su correo electrónico
    public function get_user_by_email($email) {
        return $this->db->get_where($this->table, ['email' => $email])->row_array();  // Retorna un array con los datos del usuario
    }
}
