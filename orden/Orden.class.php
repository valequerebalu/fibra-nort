<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/fibra-nort/core/Database.php';
class Orden
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    // Métodos relacionados con las órdenes pueden ir aquí
}