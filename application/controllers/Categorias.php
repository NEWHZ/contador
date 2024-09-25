<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Categorias extends CI_Controller {

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
        $this->load->model('ModeloCategoria');
        $this->load->helper('url');
        $this->load->library('session');
        $this->load->library('form_validation');
    }

    // Mostrar la lista de categorías
    public function index()
    {
        $data['categorias'] = $this->ModeloCategoria->getAllCategorias();
        $this->load->view('categorias/index', $data);
    }

    // Guardar una nueva categoría
    public function store()
    {
        $this->form_validation->set_rules('nombre', 'Nombre', 'required');
        $this->form_validation->set_rules('precio', 'Precio', 'required|numeric');

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', validation_errors());
        } else {
            $data = [
                'nombre' => $this->input->post('nombre'),
                'precio' => $this->input->post('precio'),
                'borrado_logico' => 0 // Asegurarse de que la categoría no esté eliminada lógicamente
            ];

            if ($this->ModeloCategoria->insertCategoria($data)) {
                $this->session->set_flashdata('success', 'Categoría creada con éxito.');
            } else {
                $this->session->set_flashdata('error', 'Error al insertar en la base de datos.');
            }
        }

        redirect('categorias');
    }

    // Obtener los datos de una categoría para editar
    public function edit($id)
    {
        $categoria = $this->ModeloCategoria->getCategoriaById($id);

        if (empty($categoria)) {
            show_404();
        } else {
            echo json_encode($categoria);
        }
    }

    // Actualizar una categoría
    public function update($id)
    {
        $this->form_validation->set_rules('nombre', 'Nombre', 'required');
        $this->form_validation->set_rules('precio', 'Precio', 'required|numeric');

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', validation_errors());
        } else {
            $data = [
                'nombre' => $this->input->post('nombre'),
                'precio' => $this->input->post('precio')
            ];

            if ($this->ModeloCategoria->updateCategoria($id, $data)) {
                $this->session->set_flashdata('success', 'Categoría actualizada con éxito.');
            } else {
                $this->session->set_flashdata('error', 'Error al actualizar la base de datos.');
            }
        }

        redirect('categorias');
    }

    // Eliminar (borrado lógico) una categoría
    public function delete($id)
    {
        if ($this->ModeloCategoria->deleteCategoria($id)) {
            $this->session->set_flashdata('success', 'Categoría eliminada con éxito.');
        } else {
            $this->session->set_flashdata('error', 'Error al eliminar la categoría.');
        }
        redirect('categorias');
    }
}
