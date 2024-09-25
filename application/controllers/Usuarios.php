<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Usuarios extends CI_Controller {

    public function __construct() {
        parent::__construct();
        if (!$this->session->userdata('logged_in')) {
            // Si no ha iniciado sesión, redirigir al login
            redirect('auth/login');
        }
        if ($this->session->userdata('role_id') != 1) { // 1 es para 'admin'
            $this->session->set_flashdata('error', 'No tienes acceso a esta sección.');
            redirect('asignarTiempo'); // Redirige a la página de usuario regular si no es admin
        }
        // Cargar el modelo ModeloUsuario
        $this->load->model('ModeloUsuario');
        $this->load->helper('url');
        $this->load->library('session');
        $this->load->library('form_validation');
    }

    // Mostrar la lista de todos los usuarios
    public function index() {
        $data['usuarios'] = $this->ModeloUsuario->getAllUsuarios();
        $this->load->view('usuarios/index', $data);
    }

    // Mostrar solo los usuarios pendientes
    public function pendientes() {
        $data['usuarios'] = $this->ModeloUsuario->getUsuariosPendientes();
        $this->load->view('usuarios/pendientes', $data);
    }

    // Aprobar un usuario pendiente
    public function aprobar($id) {
        if ($this->ModeloUsuario->aprobarUsuario($id)) {
            $this->session->set_flashdata('success', 'Usuario aprobado con éxito.');
        } else {
            $this->session->set_flashdata('error', 'Error al aprobar el usuario.');
        }
        redirect('usuarios/pendientes');
    }

    // Editar la información de un usuario
    public function edit($id) {
        $usuario = $this->ModeloUsuario->getUsuarioById($id);

        if (empty($usuario)) {
            show_404();
        } else {
            $data['usuario'] = $usuario;
            $this->load->view('usuarios/edit', $data);
        }
    }

    // Actualizar un usuario
    public function update() {
        // Obtener el ID del usuario desde el formulario POST
        $id = $this->input->post('id');
    
        // Verificar si se proporcionó un ID
        if (!$id) {
            $this->session->set_flashdata('error', 'ID de usuario no proporcionado.');
            redirect('usuarios');
        }
    
        // Recoger los datos del formulario
        $username = $this->input->post('username');
        $email = $this->input->post('email');
        $role_id = $this->input->post('role_id');
    
        // Validar los datos del formulario (opcional)
        if (!$username || !$email || !$role_id) {
            $this->session->set_flashdata('error', 'Por favor, complete todos los campos.');
            redirect('usuarios');
        }
    
        // Crear un arreglo con los datos a actualizar
        $data = array(
            'username' => $username,
            'email' => $email,
            'role_id' => $role_id
        );
    
        // Actualizar los datos del usuario
        if ($this->ModeloUsuario->updateUsuario($id, $data)) {
            $this->session->set_flashdata('success', 'Usuario actualizado con éxito.');
        } else {
            $this->session->set_flashdata('error', 'Hubo un problema al actualizar el usuario.');
        }
    
        // Redirigir a la lista de usuarios
        redirect('usuarios');
    }
    

    // Eliminar un usuario
    public function delete($id) {
        if ($this->ModeloUsuario->deleteUsuario($id)) {
            $this->session->set_flashdata('success', 'Usuario eliminado con éxito.');
        } else {
            $this->session->set_flashdata('error', 'Hubo un error al eliminar el usuario.');
        }
        redirect('usuarios');
    }
}
