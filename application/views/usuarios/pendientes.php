<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Usuarios Pendientes</title>
</head>
<body>
    <h2>Usuarios Pendientes</h2>

    <!-- Mostrar mensajes de éxito o error -->
    <?php if ($this->session->flashdata('success')): ?>
        <p style="color:green;"><?= $this->session->flashdata('success') ?></p>
    <?php endif; ?>
    <?php if ($this->session->flashdata('error')): ?>
        <p style="color:red;"><?= $this->session->flashdata('error') ?></p>
    <?php endif; ?>

    <!-- Tabla de usuarios pendientes -->
    <table border="1">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre de usuario</th>
                <th>Email</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($usuarios as $usuario): ?>
                <tr>
                    <td><?= $usuario['id'] ?></td>
                    <td><?= $usuario['username'] ?></td>
                    <td><?= $usuario['email'] ?></td>
                    <td>
                        <a href="<?= site_url('usuarios/aprobar/' . $usuario['id']) ?>">Aprobar</a> | 
                        <a href="<?= site_url('usuarios/delete/' . $usuario['id']) ?>" onclick="return confirm('¿Estás seguro?')">Eliminar</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <a href="<?= site_url('usuarios') ?>">Volver a la lista de todos los usuarios</a>
</body>
</html>
