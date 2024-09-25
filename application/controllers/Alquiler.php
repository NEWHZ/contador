<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Alquiler extends CI_Controller {

    public function __construct() {
        parent::__construct();
        if (!$this->session->userdata('logged_in')) {
            redirect('auth/login'); // Redirige al login si no está autenticado
        }
    
        // Verifica si el usuario tiene el rol adecuado (ejemplo: 'admin')
        if ($this->session->userdata('role_id') != 1) { // 1 es para 'admin'
            $this->session->set_flashdata('error', 'No tienes acceso a esta sección.');
            redirect('asignarTiempo'); // Redirige a la página de usuario regular si no es admin
        }
        $this->load->model('Alquiler_model'); // Cargar el modelo de alquiler
        $this->load->helper('url');
        $this->load->library('session');
    }

    // Método predeterminado (index) para redirigir al historial
    public function index() {
        redirect('alquiler/historial');
    }

  // Método para registrar el alquiler
public function registrarAlquiler() {
    // Log para verificar que el método se llama
    log_message('info', 'registrarAlquiler method called.');

    $espacio_id = $this->input->post('espacio_id');
    $tiempo_uso = $this->input->post('tiempo_uso'); // Tiempo en horas

    // Verificar que los datos están llegando
    log_message('info', 'Datos recibidos - Espacio ID: ' . $espacio_id . ' Tiempo de uso: ' . $tiempo_uso);

    // Obtener el precio por hora del espacio
    $precio_aplicado = $this->Alquiler_model->obtenerPrecioCategoria($espacio_id);
    $total_pago = $precio_aplicado * $tiempo_uso;

    // Datos para guardar en la tabla historial_alquiler
    $data = array(
        'espacio_id' => $espacio_id,
        'precio_aplicado' => $precio_aplicado,
        'tiempo_uso' => $tiempo_uso,
        'total_pago' => $total_pago
    );

    // Log de los datos que se van a insertar
    log_message('info', 'Datos a guardar: ' . json_encode($data));

    // Guardar el registro en la base de datos
    if ($this->Alquiler_model->guardarAlquiler($data)) {
        log_message('info', 'Alquiler registrado con éxito.');
        echo json_encode(array('status' => 'success', 'message' => 'Alquiler registrado con éxito'));
    } else {
        log_message('error', 'Error al registrar el alquiler.');
        echo json_encode(array('status' => 'error', 'message' => 'Error al registrar el alquiler'));
    }
}

    // Método para mostrar el historial de alquileres
    public function historial() {
        // Inicializamos las variables
        $data['total_pago'] = 0;  // Inicializar el total en 0 siempre para evitar errores
        $data['historial'] = [];  // Inicializar el historial vacío para evitar errores
    
        // Verificamos si se envió el formulario de filtros (usando POST)
        if ($this->input->server('REQUEST_METHOD') === 'POST') {
            // Aplicar filtros si se envían datos POST
            $filters = array(
                'espacio_id' => $this->input->post('espacio_id'),
                'categoria_id' => $this->input->post('categoria_id'),
                'fecha_desde' => $this->input->post('fecha_desde'),
                'fecha_hasta' => $this->input->post('fecha_hasta')
            );
    
            $this->load->library('form_validation');
            $this->form_validation->set_rules('fecha_desde', 'Fecha Desde', 'trim');
            $this->form_validation->set_rules('fecha_hasta', 'Fecha Hasta', 'trim');
    
            if ($this->form_validation->run() !== FALSE) {
                // Si el formulario de filtro es válido, aplicamos los filtros
                $data['historial'] = $this->Alquiler_model->filtrarHistorial($filters);
    
                // Calcular el total de pagos filtrados
                foreach ($data['historial'] as $alquiler) {
                    $data['total_pago'] += $alquiler['total_pago'];
                }
            }
        } else {
            // Si no se han enviado filtros, cargar todo el historial sin filtrar
            $data['historial'] = $this->Alquiler_model->getHistorialAlquiler();
        }
    
        // Recuperar todos los espacios y categorías para los filtros
        $data['espacios'] = $this->db->get('espacios_trabajo')->result_array();
        $data['categorias'] = $this->db->get('categorias')->result_array();
    
        // Cargar la vista con los datos
        $this->load->view('alquiler/historial', $data);
    }
    
    
    
    
    
}
