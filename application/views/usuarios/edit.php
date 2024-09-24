<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Usuario</title>
</head>
<body>
    <h2>Editar Usuario</h2>

    <!-- Mostrar mensajes de error -->
    <?php if ($this->session->flashdata('error')): ?>
        <p style="color:red;"><?= $this->session->flashdata('error') ?></p>
    <?php endif; ?>

    <!-- Formulario para editar usuario -->
    <form action="<?= site_url('usuarios/update/' . $usuario['id']) ?>" method="POST">
        <label for="username">Nombre de usuario:</label>
        <input type="text" name="username" value="<?= $usuario['username'] ?>" required><br>

        <label for="email">Correo electrónico:</label>
        <input type="email" name="email" value="<?= $usuario['email'] ?>" required><br>

        <label for="role_id">Rol:</label>
        <select name="role_id">
            <option value="1" <?= $usuario['role_id'] == 1 ? 'selected' : '' ?>>Admin</option>
            <option value="2" <?= $usuario['role_id'] == 2 ? 'selected' : '' ?>>Usuario</option>
        </select><br>

        <button type="submit">Guardar cambios</button>
    </form>

    <a href="<?= site_url('usuarios') ?>">Volver</a>
</body>
</html>
