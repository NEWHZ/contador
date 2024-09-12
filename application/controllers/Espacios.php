<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Espacios extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('ModeloEspacio');
        $this->load->helper('url');
        $this->load->library('session');
        $this->load->library('form_validation');
    }

    // Mostrar la lista de espacios de trabajo (tablero)
    public function index()
    {
        $data['espacios'] = $this->ModeloEspacio->getAllEspacios();
        $this->load->view('espacios/index', $data);  // Carga la vista del tablero
    }

    // Guardar un nuevo espacio de trabajo
    
    public function store()
{
    // Validación del formulario
    $this->form_validation->set_rules('nombre', 'Nombre', 'required');
    $this->form_validation->set_rules('estado', 'Estado', 'required');
    $this->form_validation->set_rules('color', 'Color de Fondo', 'required');

    if ($this->form_validation->run() == FALSE) {
        $this->session->set_flashdata('error', validation_errors());
        redirect('espacios');
    } else {
        // Procesar los datos del formulario
        $data = [
            'nombre' => $this->input->post('nombre'),
            'descripcion' => $this->input->post('descripcion'),
            'estado' => $this->input->post('estado'),
            'color_fondo' => $this->input->post('color'),
        ];

        // Verificar si se subió una imagen
        if (!empty($_FILES['imagen']['tmp_name'])) {
            // Leer el contenido binario de la imagen
            $image_data = file_get_contents($_FILES['imagen']['tmp_name']);
            $data['imagen'] = $image_data; // Guardar la imagen como binario
        }

        // Insertar en la base de datos
        $this->ModeloEspacio->insertEspacio($data);
        $this->session->set_flashdata('success', 'Espacio creado con éxito.');
        redirect('espacios');
    }
}

    private function uploadImage()
    {
        $config['upload_path'] = './uploads/';
        $config['allowed_types'] = 'gif|jpg|png';
        $config['max_size'] = 2048; // Tamaño máximo 2MB
        $config['encrypt_name'] = TRUE; // Evita conflictos de nombre
    
        $this->load->library('upload', $config);
    
        if ($this->upload->do_upload('imagen')) {
            return $this->upload->data('file_name');
        } else {
            return null; // Si no hay imagen subida, retorna null
        }
    }
    public function update($id)
    {
        // Validación del formulario
        $this->form_validation->set_rules('nombre', 'Nombre', 'required');
        $this->form_validation->set_rules('estado', 'Estado', 'required');
        $this->form_validation->set_rules('color', 'Color de Fondo', 'required');
    
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('espacios/edit/'.$id);
        } else {
            // Procesar los datos del formulario
            $data = [
                'nombre' => $this->input->post('nombre'),
                'descripcion' => $this->input->post('descripcion'),
                'estado' => $this->input->post('estado'),
                'color_fondo' => $this->input->post('color')
            ];
    
            // Verificar si el usuario ha subido una nueva imagen
            if (!empty($_FILES['imagen']['tmp_name'])) {
                // Leer el contenido binario de la imagen
                $image_data = file_get_contents($_FILES['imagen']['tmp_name']);
                $data['imagen'] = $image_data; // Guardar la imagen como binario
            }
    
            // Actualizar en la base de datos
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


    
}
