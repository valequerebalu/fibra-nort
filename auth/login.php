<?php
// Controlador de Login
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Internet Hogar</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>
<body class="bg-gray-100">

    <div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-md p-8">
            <div class="text-center mb-8">
                <div class="bg-indigo-600 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-user text-white text-2xl"></i>
                </div>
                <h1 class="text-3xl font-bold text-gray-900">Internet Hogar</h1>
                <p class="text-gray-600 mt-2">Sistema de Gestión de Servicios</p>
            </div>

            <?php if (isset($_GET['error'])): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <strong class="font-bold">Error!</strong>
                    <span class="block sm:inline">
                        <?php 
                        if ($_GET['error'] == 'invalid') echo "Credenciales incorrectas.";
                        elseif ($_GET['error'] == 'empty') echo "Por favor complete todos los campos.";
                        else echo "Ocurrió un problema al iniciar sesión.";
                        ?>
                    </span>
                </div>
            <?php endif; ?>

            <form action="/fibra-nort/auth/auth_controller.php" method="POST" class="space-y-6">
                <!-- Operation for Controller -->
                <input type="hidden" name="op" value="login">

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Correo Electrónico
                    </label>
                    <div class="relative">
                        <div class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 w-5 h-5 flex items-center justify-center">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <input
                            type="email"
                            name="email"
                            placeholder="usuario@ejemplo.com"
                            class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent outline-none bg-white"
                            required
                        />
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Contraseña
                    </label>
                    <div class="relative">
                        <div class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 w-5 h-5 flex items-center justify-center">
                            <i class="fas fa-lock"></i>
                        </div>
                        <input
                            type="password"
                            name="password"
                            placeholder="••••••••"
                            required
                            class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent outline-none bg-white"
                        />
                    </div>
                </div>

                <button
                    type="submit"
                    class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-3 rounded-lg transition duration-200 shadow-md transform hover:-translate-y-0.5"
                >
                    Iniciar Sesión
                </button>
            </form>

            <div class="mt-8 pt-6 border-t border-gray-200">
                <p class="text-xs text-gray-500 text-center">
                    &copy; <?php echo date('Y'); ?> Internet Hogar. Todos los derechos reservados.
                </p>
            </div>
        </div>
    </div>

</body>
</html>