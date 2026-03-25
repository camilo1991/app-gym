<?php
class Sesion {
    private $db;

    public function __construct($pdo) {
        $this->db = $pdo;
    }

    public function finalizarSesion($user_id, $segundos_totales) {
        $minutos = round($segundos_totales / 60);
        $sql = "INSERT INTO sesiones (user_id, hora_inicio, hora_fin, duracion_minutos) 
                VALUES (?, DATE_SUB(NOW(), INTERVAL ? SECOND), NOW(), ?)";
        return $this->db->prepare($sql)->execute([$user_id, $segundos_totales, $minutos]);
    }
}