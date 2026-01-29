<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/fibra-nort/core/Database.php';

class AuthModel {
    private $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    public function login($email, $password) {
        try {
            // 1. Buscamos el usuario por email
            $sql = "SELECT u.id, u.name, u.email, u.password, u.role_id, r.name as role_name, u.status 
                    FROM users u
                    INNER JOIN roles r ON u.role_id = r.id
                    WHERE email = :email AND u.status = 1";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':email', $email);
            $stmt->execute();

            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user) {
                // 2. Verificamos la contraseña
                // NOTA: Si usas passwords en texto plano en la BD, cambia esto a: if ($password === $user['password'])
                // Recomendado: password_verify($password, $user['password'])
                
                // Para este ejemplo asumiré texto plano si no están encriptadas, 
                // pero dejaré comentado cómo debería ser si usaras password_hash()
                
                // Opción A: Texto plano (INSEGURO - Solo para pruebas si tu BD ya es así)
                 if ($password === $user['password']) {
                     // Eliminamos password del array por seguridad
                     unset($user['password']);
                     return $user;
                 }
                 
                // Opción B: Hasheada (RECOMENDADO)
                // if (password_verify($password, $user['password'])) {
                //    unset($user['password']);
                //    return $user;
                // }
            }

            return false;
        } catch (Exception $e) {
            // Log del error
            error_log("Error en login: " . $e->getMessage());
            return false;
        }
    }
}
