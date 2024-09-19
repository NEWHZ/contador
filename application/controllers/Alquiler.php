<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Alquiler extends CI_Controller {

    public function __construct() {
        parent::__construct();
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
        $data['historial'] = $this->Alquiler_model->getHistorialAlquiler();
        $this->load->view('alquiler/historial', $data);
    }

    // Método opcional para aplicar filtros al historial
    public function filtrarHistorial() {
        $filters = array(
            'espacio_id' => $this->input->post('espacio_id'),
            'fecha_desde' => $this->input->post('fecha_desde'),
            'fecha_hasta' => $this->input->post('fecha_hasta')
        );

        $data['historial'] = $this->Alquiler_model->filtrarHistorial($filters);
        $this->load->view('alquiler/historial', $data);
    }
}
