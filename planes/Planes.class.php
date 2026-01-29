<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/fibra-nort/core/Database.php';
class Planes
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function listar_planes()
    {
        try {
            $stmt = $this->db->prepare("SELECT cp.id,
                                                cp.code_client_plan ,
                                                c.document_number,
                                                cp.client_id,
                                                concat(c.name_or_company_name, ' ', c.paternal_surname, ' ', c.maternal_surname) as client_name,
                                                cp.plan_id,
                                                p.name as plan_name,
                                                p.monthly_price,
                                                p.speed_mbps,
                                                cp.start_date,
                                                cp.end_date,
                                                cp.status
                                            from client_plans cp
                                            inner join clients c on cp.client_id = c.id
                                            inner join plans p on cp.plan_id = p.id
                                            where cp.status = 1");
            $stmt->execute();
            
            if ($stmt->rowCount() > 0) {
                return [
                    "estado" => 1,
                    "mensaje" => "exito",
                    "data" => $stmt->fetchAll(PDO::FETCH_ASSOC)
                ];
            }

            return ["estado" => 0, "mensaje" => "No hay planes registrados", "data" => []];
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function obtener_plan($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM planes WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
