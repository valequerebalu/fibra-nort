
<?php
session_start();
require_once 'auth_model.php';

$op = $_POST['op'] ?? $_GET['op'] ?? '';

if ($op == 'login') {
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $password = $_POST['password']; // No sanitizar passwords, pueden contener caracteres especiales

    if (empty($email) || empty($password)) {
        header('Location: /fibra-nort/auth/login.php?error=empty');
        exit;
    }

    $auth = new AuthModel();
    $user = $auth->login($email, $password);

    if ($user) {
        // Login exitoso
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['role_id'] = $user['role_id'];
        $_SESSION['role'] = $user['role_name']; // Asegúrate que tu tabla users tenga columna 'role'
        
        // Redirigir al dashboard
        header('Location: /fibra-nort/public/index.php');
        exit;
    } else {
        // Login fallido
        header('Location: /fibra-nort/auth/login.php?error=invalid');
        exit;
    }

} elseif ($op == 'logout') {
    session_destroy();
    header('Location: /fibra-nort/auth/login.php');
    exit;
} else {
    // Si acceden directamente sin op
    header('Location: /fibra-nort/auth/login.php');
    exit;
}