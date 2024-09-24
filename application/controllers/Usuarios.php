<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Usuarios extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('ModeloUsuario');
        $this->load->helper('url');
        $this->load->library('session');
        $this->load->library('form_validation');
        $this->load->model('User_model'); // Cargar el modelo User_model
    }

    // Mostrar la lista de todos los usuarios
    public function index()
    {
        $data['usuarios'] = $this->ModeloUsuario->getAllUsuarios();
        $this->load->view('usuarios/index', $data);
    }

    // Mostrar solo los usuarios pendientes
    public function pendientes()
    {
        $data['usuarios'] = $this->ModeloUsuario->getUsuariosPendientes();
        $this->load->view('usuarios/pendientes', $data);
    }

    // Aprobar un usuario pendiente
    public function aprobar($id)
    {
        if ($this->ModeloUsuario->aprobarUsuario($id)) {
            $this->session->set_flashdata('success', 'Usuario aprobado con éxito.');
        } else {
            $this->session->set_flashdata('error', 'Error al aprobar el usuario.');
        }
        redirect('usuarios/pendientes');
    }

    // Editar la información de un usuario
    public function edit($id)
    {
        $usuario = $this->ModeloUsuario->getUsuarioById($id);

        if (empty($usuario)) {
            show_404();
        } else {
            $data['usuario'] = $usuario;
            $this->load->view('usuarios/edit', $data);
        }
    }

    // Actualizar un usuario
    public function update($id) {
        // Obtener los datos del formulario
        $username = $this->input->post('username');
        $email = $this->input->post('email');
        $role_id = $this->input->post('role_id');

        // Validación y actualización
        $data = array(
            'username' => $username,
            'email' => $email,
            'role_id' => $role_id
        );

        // Llamar al modelo para actualizar el usuario
        if ($this->User_model->update_user($id, $data)) {
            // Establecer mensaje de éxito
            $this->session->set_flashdata('success', 'Usuario actualizado con éxito.');
        } else {
            // Establecer mensaje de error
            $this->session->set_flashdata('error', 'Hubo un error al actualizar el usuario.');
        }

        // Redirigir a la lista de usuarios
        redirect('usuarios');
    }


    // Eliminar un usuario
   

    public function delete($id) {
        if ($this->User_model->delete_user($id)) {
            $this->session->set_flashdata('success', 'Usuario eliminado con éxito.');
        } else {
            $this->session->set_flashdata('error', 'Hubo un error al eliminar el usuario.');
        }
        redirect('usuarios');
    }
}
