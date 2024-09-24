<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class AuthController extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('User_model');  // Cargar el modelo de usuario
        $this->load->library('session');   // Cargar la biblioteca de sesión
        $this->load->helper('url');        // Cargar el helper de URL
        $this->load->helper('form');       // Cargar el helper de formularios
    }

    // Mostrar formulario de registro
    public function register() {
        // Verificar si el usuario ya está logueado
        if ($this->session->userdata('logged_in')) {
            redirect('asignarTiempo');  // Redirigir a la página principal si ya está logueado
        }
        $this->load->view('auth/register');  // Cargar la vista del formulario de registro
    }

    // Procesar el registro de un nuevo usuario
    public function process_register() {
        $username = $this->input->post('username');
        $email = $this->input->post('email');
        $password = $this->input->post('password');
    
        // Verificar si el nombre de usuario ya existe
        if ($this->User_model->get_user_by_username($username)) {
            $this->session->set_flashdata('error', 'El nombre de usuario ya está en uso. Por favor, inicia sesión.');
            redirect('auth/register');
        }
    
        // Verificar si el correo electrónico ya existe
        if ($this->User_model->get_user_by_email($email)) {
            $this->session->set_flashdata('error', 'El correo electrónico ya está registrado. Por favor, inicia sesión.');
            redirect('auth/register');
        }
    
        // Validar la contraseña (mínimo 8 caracteres, al menos una mayúscula, una minúscula, un número y un carácter especial)
        if (!preg_match('/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/', $password)) {
            $this->session->set_flashdata('error', 'La contraseña debe tener al menos 8 caracteres, incluyendo una mayúscula, una minúscula, un número y un carácter especial.');
            redirect('auth/register');
        }
    
        // Si todo está correcto, proceder con el registro
        $data = array(
            'username' => $username,
            'email'    => $email,
            'password' => password_hash($password, PASSWORD_BCRYPT),  // Hashear la contraseña
            'role_id'  => 2,  // Rol por defecto: usuario
            'status'   => 0   // Estado pendiente (espera de aprobación)
        );
    
        // Insertar el usuario en la base de datos
        if ($this->User_model->create_user($data)) {
            $this->session->set_flashdata('success', 'Registro exitoso. Por favor, espera la aprobación del administrador.');
            redirect('auth/login');
        } else {
            $this->session->set_flashdata('error', 'Hubo un error al registrar el usuario.');
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

        // Verificar que el usuario exista y que la contraseña sea correcta
        if ($user && password_verify($password, $user->password)) {
            // Verificar si el usuario está aprobado
            if ($user->status == 0) {
                $this->session->set_flashdata('error', 'Tu cuenta está pendiente de aprobación.');
                redirect('auth/login');
            }
        
            // Almacenar los datos del usuario en la sesión
            $this->session->set_userdata(array(
                'user_id'   => $user->id,
                'role_id'   => $user->role_id,
                'username'  => $user->username,
                'logged_in' => true
            ));
        
            // Redirigir a la página de asignar tiempo (para ambos roles)
            redirect('asignarTiempo');  // Tanto los administradores como los usuarios regulares van a asignarTiempo
        } else {
            // Si las credenciales son incorrectas, mostrar un mensaje de error
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
}
