<?php
class User_model extends CI_Model {

    // Crear nuevo usuario
    public function create_user($data) {
        return $this->db->insert('users', $data);
    }

    // Obtener un usuario por su nombre de usuario
    public function get_user_by_username($username) {
        $this->db->where('username', $username);
        $query = $this->db->get('users');
        return $query->row();
    }

    // Obtener un usuario por su correo electrónico
    public function get_user_by_email($email) {
        $this->db->where('email', $email);
        $query = $this->db->get('users');
        return $query->row();  // Retorna una sola fila
    }

    // Obtener todos los usuarios pendientes
    public function get_pending_users() {
        $this->db->where('status', 0);  // Solo usuarios pendientes
        $query = $this->db->get('users');
        return $query->result();
    }

    // Aprobar o actualizar usuario
    public function update_user($user_id, $data) {
        $this->db->where('id', $user_id);
        return $this->db->update('users', $data);
    }

    // Eliminar usuario
    public function delete_user($user_id) {
        $this->db->where('id', $user_id);
        return $this->db->delete('users');
    }
}
