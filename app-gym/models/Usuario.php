<?php
class Usuario {
    private $db;

    public function __construct($pdo) {
        $this->db = $pdo;
    }

    public function registrar($data) {
    try {
        // Preparamos la consulta con todos los campos de tu vista
        $sql = "INSERT INTO usuarios_v2 (nombre, username, email, password, peso_actual, peso_ideal) 
                VALUES (:nom, :usr, :eml, :pass, :pa, :pi)";
        $stmt = $this->db->prepare($sql);
        
        return $stmt->execute([
            'nom'  => $data['nombre'],
            'usr'  => $data['username'],
            'eml'  => $data['username'] . "@gym.com", // Generamos un email ficticio para no romper el NOT NULL
            'pass' => $data['password'],
            'pa'   => !empty($data['peso_actual']) ? $data['peso_actual'] : 0,
            'pi'   => !empty($data['peso_ideal']) ? $data['peso_ideal'] : 0
        ]);
    } catch (PDOException $e) {
        // Esto te dirá el error exacto si vuelve a fallar
        die("Error en el registro SQL: " . $e->getMessage());
    }
}

    public function login($user, $pass) {
        $stmt = $this->db->prepare("SELECT * FROM usuarios_v2 WHERE username = ?");
        $stmt->execute([$user]);
        $u = $stmt->fetch();
        if ($u && password_verify($pass, $u['password'])) {
            return $u;
        }
        return false;
    }

    public function getMetas($id) {
        $stmt = $this->db->prepare("SELECT * FROM usuarios_v2 WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
}