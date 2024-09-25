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
        .error-message {
            color: red;
            font-size: 0.9em;
            display: none;
        }
        #passwordStrength {
            margin-top: 5px;
            font-size: 0.9em;
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

        // Función para verificar la fortaleza de la contraseña y mostrar comentarios en tiempo real
        function checkPasswordStrength(password) {
            var strength = 0;
            var tips = "";

            if (password.length < 8) {
                tips += "Debe tener al menos 8 caracteres. ";
            } else {
                strength += 1;
            }

            if (password.match(/[a-z]/) && password.match(/[A-Z]/)) {
                strength += 1;
            } else {
                tips += "Use letras mayúsculas y minúsculas. ";
            }

            if (password.match(/\d/)) {
                strength += 1;
            } else {
                tips += "Incluya al menos un número. ";
            }

            if (password.match(/[^a-zA-Z\d]/)) {
                strength += 1;
            } else {
                tips += "Incluya al menos un carácter especial. ";
            }

            var strengthElement = document.getElementById("passwordStrength");

            if (strength < 2) {
                strengthElement.textContent = "Fácil de adivinar. " + tips;
                strengthElement.style.color = "red";
                strengthElement.style.display = "block";
            } else if (strength === 2) {
                strengthElement.textContent = "Dificultad media. " + tips;
                strengthElement.style.color = "orange";
                strengthElement.style.display = "block";
            } else if (strength === 3) {
                strengthElement.textContent = "Difícil. " + tips;
                strengthElement.style.color = "black";
                strengthElement.style.display = "block";
            } else {
                strengthElement.textContent = "Extremadamente difícil. " + tips;
                strengthElement.style.color = "green";
                strengthElement.style.display = "block";
            }
        }

        // Validar el formulario antes de enviar
        function checkForm(event, form) {
            event.preventDefault();  // Detener el envío del formulario

            let valid = true;
            let password1 = form.pwd1.value;
            let password2 = form.pwd2.value;
            let username = form.username.value;

            document.getElementById('passwordError').style.display = 'none';
            document.getElementById('confirmPasswordError').style.display = 'none';
            document.getElementById('usernameError').style.display = 'none';

            if (username == "") {
                document.getElementById('usernameError').textContent = 'Debe escribir un nombre de usuario.';
                document.getElementById('usernameError').style.display = 'block';
                valid = false;
            }

            var re = /^\w+$/;
            if (!re.test(username)) {
                document.getElementById('usernameError').textContent = 'El nombre de usuario solo debe contener letras, números y guiones bajos.';
                document.getElementById('usernameError').style.display = 'block';
                valid = false;
            }

            if (password1 !== password2) {
                document.getElementById('confirmPasswordError').textContent = 'Las contraseñas no coinciden.';
                document.getElementById('confirmPasswordError').style.display = 'block';
                valid = false;
            }

            if (valid) {
                // Mostrar la alerta de registro exitoso y luego enviar el formulario
                Swal.fire({
                    title: 'Registro Exitoso',
                    text: 'El formulario ha sido enviado correctamente.',
                    icon: 'success',
                    showConfirmButton: true,
                }).then(() => {
                    form.submit();  // Enviar el formulario manualmente después de mostrar la alerta
                });
            }
        }
    </script>
</head>
<body>

    <div class="registration-container">
        <h2>Registro</h2>

        <!-- Formulario de registro -->
        <?php echo form_open('auth/process_register', array('method' => 'POST', 'onsubmit' => 'checkForm(event, this);')); ?>

            <div class="mb-3">
                <label for="username" class="form-label">Nombre de usuario</label>
                <input type="text" class="form-control" id="username" name="username" required>
                <div id="usernameError" class="error-message">Debe escribir un nombre de usuario.</div>
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>

            <!-- Contraseña con ícono dentro del campo -->
            <div class="mb-3">
                <label for="password" class="form-label">Contraseña</label>
                <div class="input-group">
                    <input type="password" class="form-control" id="password" name="pwd1" oninput="checkPasswordStrength(this.value)" required>
                    <span class="input-group-text" onclick="togglePassword('password', 'togglePasswordIcon')">
                        <i class="fas fa-eye" id="togglePasswordIcon"></i>
                    </span>
                </div>
                <div id="passwordStrength" class="error-message"></div> <!-- Mensaje de fortaleza de la contraseña -->
                <div id="passwordError" class="error-message"></div> <!-- Mensaje de error para contraseña -->
            </div>

            <!-- Confirmación de contraseña con ícono dentro del campo -->
            <div class="mb-3">
                <label for="confirm_password" class="form-label">Confirmar Contraseña</label>
                <div class="input-group">
                    <input type="password" class="form-control" id="confirm_password" name="pwd2" required>
                    <span class="input-group-text" onclick="togglePassword('confirm_password', 'toggleConfirmPasswordIcon')">
                        <i class="fas fa-eye" id="toggleConfirmPasswordIcon"></i>
                    </span>
                </div>
                <div id="confirmPasswordError" class="error-message"></div> <!-- Mensaje de error para confirmación de contraseña -->
            </div>

            <button type="submit" class="btn btn-primary">Registrarse</button>
        <?php echo form_close(); ?>

        <a href="<?php echo site_url('auth/login'); ?>">Iniciar sesión</a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
