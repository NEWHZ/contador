<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class AuthController extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('User_model');  // Cargar el modelo de usuario
        $this->load->library('session');   // Cargar la biblioteca de sesión
        $this->load->library('form_validation'); // Cargar validación de formularios
        $this->load->helper('url');        // Cargar el helper de URL
    }

    // Mostrar formulario de registro
    public function register() {
        $this->load->view('auth/register');  // Cargar la vista del formulario de registro
    }

    // Procesar el registro de un nuevo usuario
    public function process_register() {
        $username = $this->input->post('username');
        $email = $this->input->post('email');
        $password = $this->input->post('pwd1');
    
        // Verificar si el nombre de usuario ya existe
        if ($this->User_model->get_user_by_username($username)) {
            $this->session->set_flashdata('error', 'El nombre de usuario ya está en uso.');
            redirect('auth/register');
            return;
        }
    
        // Verificar si el correo electrónico ya está registrado
        if ($this->User_model->get_user_by_email($email)) {
            $this->session->set_flashdata('error', 'El correo electrónico ya está registrado.');
            redirect('auth/register');
            return;
        }
    
        // Si pasa las validaciones, crear el usuario
        $data = array(
            'username' => $username,
            'email' => $email,
            'password' => password_hash($password, PASSWORD_BCRYPT),  // Hashear la contraseña
            'role_id' => 2,  // Rol de usuario
            'status' => 0    // Estado pendiente
        );
    
        if ($this->User_model->create_user($data)) {
            $this->session->set_flashdata('success', 'Registro exitoso. Espera la aprobación del administrador.');
            redirect('auth/login');
        } else {
            $this->session->set_flashdata('error', 'Error al registrar el usuario. Inténtalo nuevamente.');
            redirect('auth/register');
        }
    }
    
    // Mostrar formulario de login
    public function login() {
        // Verificar si el usuario ya está logueado
        if ($this->session->userdata('logged_in')) {
            redirect('asignarTiempo');  // Redirigir a la página principal si ya está logueado
        }
        $this->load->view('auth/login');  // Cargar la vista del formulario de login
    }

    // Procesar el inicio de sesión
    public function process_login() {
        $username = $this->input->post('username');
        $password = $this->input->post('password');
    
        // Obtener el usuario por su nombre de usuario
        $user = $this->User_model->get_user_by_username($username);
    
        if ($user) {
            // Mostrar el hash de la base de datos para depurar
            log_message('info', 'Hash de la base de datos: ' . $user['password']);
    
            // Verificar que la contraseña ingresada coincida con el hash
            if (password_verify($password, $user['password'])) {
                // Verificar si el usuario está aprobado
                if ($user['status'] == 0) {
                    $this->session->set_flashdata('error', 'Tu cuenta está pendiente de aprobación.');
                    redirect('auth/login');
                }
            
                // Almacenar los datos del usuario en la sesión
                $this->session->set_userdata(array(
                    'user_id'   => $user['id'],
                    'role_id'   => $user['role_id'],
                    'username'  => $user['username'],
                    'logged_in' => true
                ));
            
                // Redirigir a la página de asignar tiempo (para ambos roles)
                redirect('asignarTiempo');  // Tanto los administradores como los usuarios regulares van a asignarTiempo
            } else {
                log_message('error', 'Contraseña incorrecta para el usuario: ' . $username);
                $this->session->set_flashdata('error', 'Contraseña incorrecta.');
                redirect('auth/login');
            }
        } else {
            // Si el usuario no existe, mostrar un mensaje de error
            log_message('error', 'Usuario no encontrado: ' . $username);
            $this->session->set_flashdata('error', 'Credenciales incorrectas.');
            redirect('auth/login');
        }
    }
    

    // Cerrar sesión
    public function logout() {
        // Destruir la sesión y redirigir al login
        $this->session->sess_destroy();
        redirect('auth/login');
    }
    public function check_unique() {
        $username = $this->input->post('username');
        $email = $this->input->post('email');
    
        // Verificar si el nombre de usuario ya existe
        $username_exists = $this->User_model->get_user_by_username($username);
    
        // Verificar si el correo electrónico ya está registrado
        $email_exists = $this->User_model->get_user_by_email($email);
    
        $response = [
            'username_exists' => !empty($username_exists),
            'email_exists' => !empty($email_exists)
        ];
    
        echo json_encode($response);  // Retorna la respuesta en formato JSON
    }
    
    
}
