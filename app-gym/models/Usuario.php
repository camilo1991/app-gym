<?php
class Usuario {
    private $db;

    public function __construct($pdo) {
        $this->db = $pdo;
    }

    public function registrar($data) {
        $hash = password_hash($data['password'], PASSWORD_BCRYPT);
        $sql = "INSERT INTO usuarios (nombre, username, password, peso_actual, peso_ideal) VALUES (?, ?, ?, ?, ?)";
        return $this->db->prepare($sql)->execute([
            $data['nombre'], $data['username'], $hash, $data['peso_actual'], $data['peso_ideal']
        ]);
    }

    public function login($user, $pass) {
        $stmt = $this->db->prepare("SELECT * FROM usuarios WHERE username = ?");
        $stmt->execute([$user]);
        $u = $stmt->fetch();
        if ($u && password_verify($pass, $u['password'])) {
            return $u;
        }
        return false;
    }

    public function getMetas($id) {
        $stmt = $this->db->prepare("SELECT * FROM usuarios WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
}