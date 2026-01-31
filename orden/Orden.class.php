<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/fibra-nort/core/Database.php';
class Orden
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function listar_ordenes()
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
                        o.order_states_id,
                        os.name AS state,
                        o.status AS status
                    FROM service_orders o
                    JOIN clients c ON o.client_id = c.id
                    LEFT JOIN users u ON o.technician_id = u.id
                    JOIN plans p ON o.plan_id = p.id
                    JOIN order_states os ON o.order_states_id = os.id
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

    public function obtener_planes()
    {
        try {
            $sql = "SELECT id, name FROM plans WHERE status = 1";
            $sentencia = $this->db->prepare($sql);
            $sentencia->execute();

            if ($sentencia->rowCount() > 0) {
                return $sentencia->fetchAll(PDO::FETCH_ASSOC);
            }

            return [];
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function obtener_cliente_dni($document_number)
    {
        try {
            $sql = "SELECT
                        c.id,
                        c.name_or_company_name,
                        c.paternal_surname,
                        c.maternal_surname,
                        c.date_birth       
                    FROM clients c
            
                    WHERE c.document_number = :document_number AND c.status = 1";

            $sentencia = $this->db->prepare($sql);
            $sentencia->bindParam(':document_number', $document_number);
            $sentencia->execute();

            if ($sentencia->rowCount() > 0) {
                return $sentencia->fetch(PDO::FETCH_ASSOC);
            }

            return null;
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function insertar($seller_id, $technician_id, $cliente_id, $scheduled_date, $scheduled_time, $plan_id, $description, $created_by)
    {
        try {
            $sql = "INSERT INTO service_orders 
                        (seller_id, technician_id, client_id, scheduled_date, scheduled_time, plan_id, description, created_by, status, order_states_id) 
                    VALUES 
                        (:seller_id, :technician_id, :client_id, :scheduled_date, :scheduled_time, :plan_id, :description, :created_by, 1, 1)";

            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':seller_id', $seller_id);
            $stmt->bindParam(':technician_id', $technician_id);
            $stmt->bindParam(':client_id', $cliente_id);
            $stmt->bindParam(':scheduled_date', $scheduled_date);
            $stmt->bindParam(':scheduled_time', $scheduled_time);
            $stmt->bindParam(':plan_id', $plan_id);
            $stmt->bindParam(':description', $description);
            $stmt->bindParam(':created_by', $created_by);

            if ($stmt->execute()) {
                return [
                    'estado' => 1,
                    'mensaje' => 'Orden insertada correctamente',
                    'data' => ['orden_id' => $this->db->lastInsertId()]
                ];
            } else {
                return [
                    'estado' => 0,
                    'mensaje' => 'Error al insertar la orden'
                ];
            }
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function obtener_por_id($id)
    {
        try {
            $sql = "SELECT
                        o.id,
                        o.code_service_orders AS code_orders,
                        o.client_id,
                        c.name_or_company_name,
                        c.paternal_surname,
                        c.maternal_surname,
                        c.document_number,
                        c.date_birth,
                        o.scheduled_date,
                        o.scheduled_time,
                        o.plan_id,
                        p.name AS plan_name,
                        o.description,
                        o.order_states_id,
                        o.status AS status
                    FROM service_orders o
                    JOIN clients c ON o.client_id = c.id
                    JOIN plans p ON o.plan_id = p.id
                    WHERE o.id = :id";

            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return null;
        }
    }

    public function editar($id,  $technician_id, $client_id, $scheduled_date, $scheduled_time, $plan_id, $description, $updated_by)
    {
        try {
            $sql = "UPDATE service_orders 
                    SET 
                        technician_id = :technician_id, 
                        client_id = :client_id, 
                        scheduled_date = :scheduled_date, 
                        scheduled_time = :scheduled_time, 
                        plan_id = :plan_id, 
                        description = :description, 
                        updated_by = :updated_by 
                    WHERE id = :id";

            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        
            $stmt->bindParam(':technician_id', $technician_id);
            $stmt->bindParam(':client_id', $client_id);
            $stmt->bindParam(':scheduled_date', $scheduled_date);
            $stmt->bindParam(':scheduled_time', $scheduled_time);
            $stmt->bindParam(':plan_id', $plan_id);
            $stmt->bindParam(':description', $description);
       
            $stmt->bindParam(':updated_by', $updated_by);

            if ($stmt->execute()) {
                return [
                    'estado' => 1,
                    'mensaje' => 'Orden actualizada correctamente'
                ];
            } else {
                return [
                    'estado' => 0,
                    'mensaje' => 'Error al actualizar la orden'
                ];
            }
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function eliminar($id)
    {
        try {
            $sql = "UPDATE service_orders SET status = 0 WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);

            if ($stmt->execute()) {
                return true;
            } else {
                return false;
            }
        } catch (Exception $e) {
            throw $e;
        }
    }
}
