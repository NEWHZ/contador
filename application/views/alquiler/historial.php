<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contador</title>

    <link rel="shortcut icon" href="<?php echo base_url('public/img/alarma-3d.png'); ?>" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .table-responsive {
            max-height: 400px;
            overflow-y: auto;
        }
        thead th {
            position: sticky;
            top: 0;
            background-color: white;
            z-index: 10;
        }

        /* Estilo para el total de pagos */
        .total-pagos {
            margin-top: 20px;
            font-size: 1.25rem;
            font-weight: bold;
            background-color: #f8f9fa;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            text-align: right;
        }

        /* Ocultar el total de pagos por defecto */
        .total-pagos.hidden {
            display: none;
        }
    </style>
</head>
<body>
<div class="container mt-5">
    <a href="<?= base_url('') ?>" class="btn btn-primary mb-3">Volver al Contador</a>
    <h2 class="mb-4">Historial de Alquileres</h2>

    <!-- Mostrar mensajes de error o éxito -->
    <?php if ($this->session->flashdata('error')): ?>
        <div class="alert alert-danger"><?= $this->session->flashdata('error') ?></div>
    <?php endif; ?>

    <!-- Formulario de filtro -->
    <form method="POST" action="<?= base_url('alquiler/filtrarHistorial') ?>" class="mb-4">
        <div class="row">
            <div class="col-md-3">
                <label for="espacio_id" class="form-label">Espacio de Trabajo</label>
                <select class="form-select" name="espacio_id" id="espacio_id">
                    <option value="">Todos</option>
                    <?php foreach ($espacios as $espacio): ?>
                        <option value="<?= $espacio['id'] ?>" <?= $this->input->post('espacio_id') == $espacio['id'] ? 'selected' : '' ?>>
                            <?= $espacio['nombre'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-3">
                <label for="categoria_id" class="form-label">Categoría</label>
                <select class="form-select" name="categoria_id" id="categoria_id">
                    <option value="">Todas</option>
                    <?php foreach ($categorias as $categoria): ?>
                        <option value="<?= $categoria['id'] ?>" <?= $this->input->post('categoria_id') == $categoria['id'] ? 'selected' : '' ?>>
                            <?= $categoria['nombre'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-3">
                <label for="fecha_desde" class="form-label">Fecha Desde</label>
                <input type="date" class="form-control" name="fecha_desde" id="fecha_desde" value="<?= $this->input->post('fecha_desde') ?>">
            </div>
            <div class="col-md-3">
                <label for="fecha_hasta" class="form-label">Fecha Hasta</label>
                <input type="date" class="form-control" name="fecha_hasta" id="fecha_hasta" value="<?= $this->input->post('fecha_hasta') ?>">
            </div>
            <div class="col-md-3 d-flex align-items-end">
                <button type="submit" class="btn btn-primary">Filtrar</button>
            </div>
        </div>
    </form>

    <!-- Mostrar la tabla si hay resultados -->
    <?php if (!empty($historial)): ?>
        <div class="table-responsive">
            <!-- Tabla del historial -->
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Espacio</th>
                        <th>Categoría</th>
                        <th>Precio Aplicado</th>
                        <th>Tiempo de Uso (horas)</th>
                        <th>Total Pago</th>
                        <th>Fecha de Alquiler</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($historial as $alquiler): ?>
                        <tr>
                            <td><?= $alquiler['id'] ?></td>
                            <td><?= $alquiler['nombre_espacio'] ?></td>
                            <td><?= isset($alquiler['nombre_categoria']) ? $alquiler['nombre_categoria'] : 'Sin categoría' ?></td>
                            <td>$<?= number_format($alquiler['precio_aplicado'], 2) ?></td>
                            <td><?= number_format($alquiler['tiempo_uso'], 2) ?> hrs</td>
                            <td>$<?= number_format($alquiler['total_pago'], 2) ?></td>
                            <td><?= date('d-m-Y H:i', strtotime($alquiler['fecha_alquiler'])) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Mostrar el total de pagos solo si hay resultados y filtros -->
        <div class="total-pagos <?= empty($historial) || !$this->input->post() ? 'hidden' : '' ?>">
            Total de Pagos Filtrados: $<?= number_format($total_pago, 2) ?>
        </div>
    <?php else: ?>
        <div class="alert alert-info">No se encontraron registros.</div>
    <?php endif; ?>
</div>

<!-- Incluir el script de temporizador en la vista de historial -->
<script src="<?php echo base_url('assets/js/timer-core.js'); ?>"></script>

</body>
</html>
