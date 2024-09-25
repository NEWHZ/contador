<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Espacios extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        if (!$this->session->userdata('logged_in')) {
            // Si no ha iniciado sesión, redirigir al login
            redirect('auth/login');
        }
        if ($this->session->userdata('role_id') != 1) { // 1 es para 'admin'
            $this->session->set_flashdata('error', 'No tienes acceso a esta sección.');
            redirect('asignarTiempo'); // Redirige a la página de usuario regular si no es admin
        }
        $this->load->model('ModeloEspacio');
        $this->load->model('ModeloCategoria');
        $this->load->helper('url');
        $this->load->library('session');
        $this->load->library('form_validation');
    }

    // Mostrar la lista de espacios de trabajo (tablero)
    public function index()
    {
        $data['espacios'] = $this->ModeloEspacio->getAllEspacios();
        $data['categorias'] = $this->ModeloCategoria->getAllCategorias(); // Obtener las categorías
        $this->load->view('espacios/index', $data);  // Cargar la vista del tablero
    }
    
    // Guardar un nuevo espacio de trabajo
    public function store()
    {
        $this->form_validation->set_rules('nombre', 'Nombre', 'required');
        $this->form_validation->set_rules('estado', 'Estado', 'required');
        $this->form_validation->set_rules('color', 'Color de Fondo', 'required');
        $this->form_validation->set_rules('categoria_id', 'Categoría', 'required|callback_check_categoria_exists'); // Validación de categoría

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('espacios');
        } else {
            $data = [
                'nombre' => $this->input->post('nombre'),
                'descripcion' => $this->input->post('descripcion'),
                'estado' => $this->input->post('estado'),
                'color_fondo' => $this->input->post('color'),
                'categoria_id' => $this->input->post('categoria_id')  // Guardar la categoría seleccionada
            ];

            if (!empty($_FILES['imagen']['tmp_name'])) {
                $image_data = file_get_contents($_FILES['imagen']['tmp_name']);
                $data['imagen'] = $image_data;
            }

            $this->ModeloEspacio->insertEspacio($data);
            $this->session->set_flashdata('success', 'Espacio creado con éxito.');
            redirect('espacios');
        }
    }

    // Validar que la categoría exista
    public function check_categoria_exists($categoria_id)
    {
        if ($this->ModeloCategoria->getCategoriaById($categoria_id)) {
            return true;
        } else {
            $this->form_validation->set_message('check_categoria_exists', 'La categoría seleccionada no es válida.');
            return false;
        }
    }

    public function update($id)
    {
        $this->form_validation->set_rules('nombre', 'Nombre', 'required');
        $this->form_validation->set_rules('estado', 'Estado', 'required');
        $this->form_validation->set_rules('color', 'Color de Fondo', 'required');
        $this->form_validation->set_rules('categoria_id', 'Categoría', 'required|callback_check_categoria_exists');

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('espacios/edit/'.$id);
        } else {
            $data = [
                'nombre' => $this->input->post('nombre'),
                'descripcion' => $this->input->post('descripcion'),
                'estado' => $this->input->post('estado'),
                'color_fondo' => $this->input->post('color'),
                'categoria_id' => $this->input->post('categoria_id') // Actualizar la categoría
            ];

            if (!empty($_FILES['imagen']['tmp_name'])) {
                $image_data = file_get_contents($_FILES['imagen']['tmp_name']);
                $data['imagen'] = $image_data;
            }

            $this->ModeloEspacio->updateEspacio($id, $data);
            $this->session->set_flashdata('success', 'Espacio actualizado con éxito.');
            redirect('espacios');
        }
    }

    public function delete($id)
    {
        // Llamar al modelo para eliminar el espacio por ID
        $this->ModeloEspacio->deleteEspacio($id);

        // Mensaje de éxito y redirección al tablero
        $this->session->set_flashdata('success', 'Espacio eliminado con éxito.');
        redirect('espacios');
    }

    public function edit($id)
    {
        $espacio = $this->ModeloEspacio->getEspacioById($id);

        if (empty($espacio)) {
            show_404();
        } else {
            // Convertir la imagen en base64 antes de enviarla como respuesta JSON
            if (!empty($espacio['imagen'])) {
                $espacio['imagen'] = base64_encode($espacio['imagen']);
            }

            echo json_encode($espacio);
        }
    }

    public function getDetails($id)
    {
        $espacio = $this->ModeloEspacio->getEspacioById($id);
    
        if (empty($espacio)) {
            // Si el espacio no existe, devolvemos un error 404
            show_404();
        } else {
            // Convertir la imagen a base64 antes de enviar la respuesta JSON
            if (!empty($espacio['imagen'])) {
                $espacio['imagen'] = base64_encode($espacio['imagen']);
            }
    
            // Devolver la respuesta en formato JSON
            echo json_encode($espacio);
        }
    }
    

}
