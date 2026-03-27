<?php
// models/Usuario.php

class Usuario {
    private $db;

    public function __construct($pdo) {
        $this->db = $pdo;
    }

    public function login($user, $pass) {
    // Usamos dos nombres de parámetros distintos para evitar el error HY093
    $sql = "SELECT * FROM usuarios_v2 WHERE (nombre = :u1 OR username = :u2) LIMIT 1";
    $stmt = $this->db->prepare($sql);
    
    // Pasamos el mismo valor ($user) a ambos marcadores
    $stmt->execute([
        'u1' => $user,
        'u2' => $user
    ]);
    
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    // Comparación de contraseña plana (123456)
    if ($usuario && $pass === $usuario['password']) {
        return $usuario;
    }
    return false;
}

    public function registrar($data) {
        try {
            $sql = "INSERT INTO usuarios_v2 (nombre, username, password, peso_actual, peso_ideal, email) 
                    VALUES (:nom, :usr, :pass, :pa, :pi, :eml)";
            $stmt = $this->db->prepare($sql);
            
            return $stmt->execute([
                'nom'  => $data['nombre'],
                'usr'  => $data['username'],
                'pass' => $data['password'],
                'pa'   => $data['peso_actual'],
                'pi'   => $data['peso_ideal'],
                'eml'  => $data['username'] . "@gym.com"
            ]);
        } catch (PDOException $e) {
            return false;
        }
    }
}