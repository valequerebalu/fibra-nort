<?php 
require_once $_SERVER['DOCUMENT_ROOT'] . '/fibra-nort/core/Database.php';

class Asignacion 
{
    private $db;

    public function __construct() 
    {
        $this->db = Database::getConnection();
    }

    public function listar_asignaciones() 
    {
         try {
            $sql = "SELECT
                        o.id,
                        o.code_service_orders AS code_orders,
                        o.client_id,
                        concat(c.name_or_company_name, ' ', c.paternal_surname, ' ', c.maternal_surname) AS client,
                        o.technician_id,
                        u.name AS technician,
                        o.scheduled_date,
                        o.scheduled_time,
                        p.name AS plan,
                        o.description,
                        o.state,
                        o.status AS status
                    FROM service_orders o
                    JOIN clients c ON o.client_id = c.id
                    LEFT JOIN users u ON o.technician_id = u.id
                    JOIN plans p ON o.plan_id = p.id
                    WHERE o.status = 1";

            $sentencia = $this->db->prepare($sql);
            $sentencia->execute();

            if ($sentencia->rowCount() > 0) {
                return [
                    "estado" => 1,
                    "mensaje" => "exito",
                    "data" => $sentencia->fetchAll(PDO::FETCH_ASSOC)
                ];
            }

            return ["estado" => 0, "mensaje" => "No hay órdenes registradas", "data" => ""];
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function obtener_orden($id) 
    {
        try {
            $sql = "SELECT
                        o.id,
                        o.code_service_orders AS code_orders,
                        o.client_id,
                        concat(c.name_or_company_name, ' ', c.paternal_surname, ' ', c.maternal_surname) AS client,
                        o.technician_id,
                        o.scheduled_date,
                        o.scheduled_time,
                        o.state
                    FROM service_orders o
                    JOIN clients c ON o.client_id = c.id
                    WHERE o.id = :id AND o.status = 1";

            $sentencia = $this->db->prepare($sql);
            $sentencia->bindParam(':id', $id, PDO::PARAM_INT);
            $sentencia->execute();

            return $sentencia->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function listar_tecnicos() 
    {
        try {
            $sql = "SELECT id, name, email 
                    FROM users 
                    WHERE role_id = 2 AND status = 1
                    ORDER BY name ASC";

            $sentencia = $this->db->prepare($sql);
            $sentencia->execute();

            return $sentencia->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function asignar_tecnico($orden_id, $technician_id) 
    {
        try {
            $sql = "UPDATE service_orders 
                    SET technician_id = :technician_id,
                        state = 'Asignado'
                    WHERE id = :orden_id AND status = 1";

            $sentencia = $this->db->prepare($sql);
            $sentencia->bindParam(':technician_id', $technician_id, PDO::PARAM_INT);
            $sentencia->bindParam(':orden_id', $orden_id, PDO::PARAM_INT);
            $sentencia->execute();

            if ($sentencia->rowCount() > 0) {
                return [
                    "estado" => 1,
                    "mensaje" => "Técnico asignado correctamente"
                ];
            }

            return [
                "estado" => 0,
                "mensaje" => "No se pudo asignar el técnico"
            ];
        } catch (Exception $e) {
            return [
                "estado" => 0,
                "mensaje" => "Error: " . $e->getMessage()
            ];
        }
    }
}