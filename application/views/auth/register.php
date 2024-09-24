<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        body {
            background-color: #f8f9fa;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .registration-container {
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }
        .registration-container h2 {
            margin-bottom: 20px;
            text-align: center;
        }
        .registration-container .btn {
            width: 100%;
        }
        .registration-container a {
            display: block;
            margin-top: 15px;
            text-align: center;
        }
        .input-group-text {
            cursor: pointer;
        }
    </style>

    <script>
        // Función para mostrar/ocultar contraseñas
        function togglePassword(inputId, iconId) {
            const passwordInput = document.getElementById(inputId);
            const icon = document.getElementById(iconId);

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }

        // Validar la contraseña en el lado del cliente y confirmación de contraseñas
        function validateForm() {
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirm_password').value;
            const passwordErrorMessage = document.getElementById('passwordError');
            const confirmPasswordErrorMessage = document.getElementById('confirmPasswordError');

            // Patrón de validación para asegurar una contraseña segura
            const regex = /^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)[A-Za-z\d]{8,}$/;

            if (!regex.test(password)) {
                passwordErrorMessage.style.display = 'block';
                passwordErrorMessage.textContent = 'La contraseña debe tener al menos 8 caracteres, incluyendo una mayúscula, una minúscula y un número.';
                return false;
            }

            if (password !== confirmPassword) {
                confirmPasswordErrorMessage.style.display = 'block';
                confirmPasswordErrorMessage.textContent = 'Las contraseñas no coinciden.';
                return false;
            }

            // Ocultar mensajes de error si todo es válido
            passwordErrorMessage.style.display = 'none';
            confirmPasswordErrorMessage.style.display = 'none';
            return true;
        }
    </script>
</head>
<body>

    <div class="registration-container">
        <h2>Registro</h2>

        <!-- Mostrar mensajes con SweetAlert2 -->
        <script>
            <?php if ($this->session->flashdata('error')): ?>
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: '<?php echo $this->session->flashdata('error'); ?>',
                    timer: 3000,
                    showConfirmButton: false
                });
            <?php endif; ?>

            <?php if ($this->session->flashdata('success')): ?>
                Swal.fire({
                    icon: 'success',
                    title: 'Registro Exitoso',
                    text: '<?php echo $this->session->flashdata('success'); ?>',
                    timer: 3000,
                    showConfirmButton: false
                });
            <?php endif; ?>
        </script>

        <!-- Formulario de registro -->
        <?php echo form_open('auth/process_register', array('onsubmit' => 'return validateForm();')); ?>
            <div class="mb-3">
                <label for="username" class="form-label">Nombre de usuario</label>
                <input type="text" class="form-control" id="username" name="username" required>
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>

            <!-- Contraseña con ícono dentro del campo -->
            <div class="mb-3">
                <label for="password" class="form-label">Contraseña</label>
                <div class="input-group">
                    <input type="password" class="form-control" id="password" name="password" required>
                    <span class="input-group-text" onclick="togglePassword('password', 'togglePasswordIcon')">
                        <i class="fas fa-eye" id="togglePasswordIcon"></i>
                    </span>
                </div>
                <div id="passwordError" style="color: red; display: none;"></div> <!-- Mensaje de error para contraseña -->
            </div>

            <!-- Confirmación de contraseña con ícono dentro del campo -->
            <div class="mb-3">
                <label for="confirm_password" class="form-label">Confirmar Contraseña</label>
                <div class="input-group">
                    <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                    <span class="input-group-text" onclick="togglePassword('confirm_password', 'toggleConfirmPasswordIcon')">
                        <i class="fas fa-eye" id="toggleConfirmPasswordIcon"></i>
                    </span>
                </div>
                <div id="confirmPasswordError" style="color: red; display: none;"></div> <!-- Mensaje de error para confirmación de contraseña -->
            </div>

            <button type="submit" class="btn btn-primary">Registrarse</button>
        <?php echo form_close(); ?>

        <a href="<?php echo site_url('auth/login'); ?>">Iniciar sesión</a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
