<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/fibra-nort/core/Database.php';

class Asignacion
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function listar_asignaciones($technician_id = null)
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
                        os.name AS status
                    FROM service_orders o
                    JOIN clients c ON o.client_id = c.id
                    JOIN order_states os ON o.order_states_id = os.id
                    LEFT JOIN users u ON o.technician_id = u.id
                    JOIN plans p ON o.plan_id = p.id
                    WHERE o.status = 1";

            if ($technician_id) {
                $sql .= " AND o.technician_id = :technician_id";
            }

            $sentencia = $this->db->prepare($sql);

            if ($technician_id) {
                $sentencia->bindParam(':technician_id', $technician_id, PDO::PARAM_INT);
            }

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
                        o.status
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
                    WHERE role_id = 3 AND status = 1
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
            // Obtener estado actual antes de modificar
            $stmtGet = $this->db->prepare("SELECT order_states_id FROM service_orders WHERE id = :orden_id");
            $stmtGet->bindParam(':orden_id', $orden_id, PDO::PARAM_INT);
            $stmtGet->execute();
            $orden = $stmtGet->fetch(PDO::FETCH_ASSOC);
            
            if (!$orden) {
                return [
                    "estado" => 0,
                    "mensaje" => "Orden no encontrada"
                ];
            }
            
            $previous_state_id = $orden['order_states_id'];
            $new_state_id = 2; // ASIGNADO
            $action_type_id = 2; // ASIGNACION
            $supervisor_id = $_SESSION['user_id'];
            
            // Actualizar cabecera (service_orders)
            $sql = "UPDATE service_orders 
                    SET technician_id = :technician_id,
                        order_states_id = :new_state_id,
                        supervisor_id = :supervisor_id
                    WHERE id = :orden_id AND status = 1";

            $sentencia = $this->db->prepare($sql);
            $sentencia->bindParam(':technician_id', $technician_id, PDO::PARAM_INT);
            $sentencia->bindParam(':new_state_id', $new_state_id, PDO::PARAM_INT);
            $sentencia->bindParam(':orden_id', $orden_id, PDO::PARAM_INT);
            $sentencia->bindParam(':supervisor_id', $supervisor_id, PDO::PARAM_INT);
            $sentencia->execute();

            if ($sentencia->rowCount() > 0) {
                // Insertar registro de trazabilidad en service_order_detail
                $sqlDetail = "INSERT INTO service_order_detail 
                              (service_order_id, previous_state_id, order_state_id, 
                               technician_id, action_type_id, assigned_by, assigned_at)
                              VALUES 
                              (:orden_id, :previous_state_id, :new_state_id, 
                               :technician_id, :action_type_id, :assigned_by, NOW())";
                
                $stmtDetail = $this->db->prepare($sqlDetail);
                $stmtDetail->bindParam(':orden_id', $orden_id, PDO::PARAM_INT);
                $stmtDetail->bindParam(':previous_state_id', $previous_state_id, PDO::PARAM_INT);
                $stmtDetail->bindParam(':new_state_id', $new_state_id, PDO::PARAM_INT);
                $stmtDetail->bindParam(':technician_id', $technician_id, PDO::PARAM_INT);
                $stmtDetail->bindParam(':action_type_id', $action_type_id, PDO::PARAM_INT);
                $stmtDetail->bindParam(':assigned_by', $supervisor_id, PDO::PARAM_INT);
                $stmtDetail->execute();
                
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
    public function aceptar_orden($orden_id)
    {
        try {
            // Obtener datos de la orden
            $sqlOrden = "SELECT technician_id, supervisor_id FROM service_orders WHERE id = :orden_id AND status = 1";
            $stmtOrden = $this->db->prepare($sqlOrden);
            $stmtOrden->bindParam(':orden_id', $orden_id, PDO::PARAM_INT);
            $stmtOrden->execute();
            $orden = $stmtOrden->fetch(PDO::FETCH_ASSOC);

            if (!$orden) {
                return [
                    "estado" => 0,
                    "mensaje" => "Orden no encontrada"
                ];
            }

            // Actualizar estado de la orden
            $previous_state_id = 2; // ASIGNADO
            $new_state_id = 3; // ACEPTADA
            $action_type_id = 3; // ACEPTACION

            $sql = "UPDATE service_orders
                    SET order_states_id = :new_state_id
                    WHERE id = :orden_id AND status = 1";

            $sentencia = $this->db->prepare($sql);
            $sentencia->bindParam(':new_state_id', $new_state_id, PDO::PARAM_INT);
            $sentencia->bindParam(':orden_id', $orden_id, PDO::PARAM_INT);
            $sentencia->execute();

            if ($sentencia->rowCount() > 0) {
                // Insertar en service_order_detail con trazabilidad
                $sqlDetail = "INSERT INTO service_order_detail
                             (service_order_id, previous_state_id, order_state_id, technician_id,
                              action_type_id, assigned_by, assigned_at, responded_at, response_note)
                             VALUES
                             (:service_order_id, :previous_state_id, :order_state_id, :technician_id,
                              :action_type_id, :assigned_by, NOW(), NOW(), :response_note)";

                $stmtDetail = $this->db->prepare($sqlDetail);
                $stmtDetail->bindParam(':service_order_id', $orden_id, PDO::PARAM_INT);
                $stmtDetail->bindParam(':previous_state_id', $previous_state_id, PDO::PARAM_INT);
                $stmtDetail->bindParam(':order_state_id', $new_state_id, PDO::PARAM_INT);
                $stmtDetail->bindParam(':technician_id', $orden['technician_id'], PDO::PARAM_INT);
                $stmtDetail->bindParam(':action_type_id', $action_type_id, PDO::PARAM_INT);
                $assigned_by = $orden['supervisor_id'];
                $stmtDetail->bindParam(':assigned_by', $assigned_by, PDO::PARAM_INT);
                $response_note = 'Orden aceptada por el técnico';
                $stmtDetail->bindParam(':response_note', $response_note, PDO::PARAM_STR);
                $stmtDetail->execute();

                return [
                    "estado" => 1,
                    "mensaje" => "Orden aceptada correctamente"
                ];
            }

            return [
                "estado" => 0,
                "mensaje" => "No se pudo aceptar la orden"
            ];
        } catch (Exception $e) {
            return [
                "estado" => 0,
                "mensaje" => "Error: " . $e->getMessage()
            ];
        }
    }

    public function rechazar_orden($orden_id, $motivo_rechazo)
    {
        try {
            // Obtener datos de la orden
            $sqlOrden = "SELECT technician_id, order_states_id, supervisor_id FROM service_orders WHERE id = :orden_id AND status = 1";
            $stmtOrden = $this->db->prepare($sqlOrden);
            $stmtOrden->bindParam(':orden_id', $orden_id, PDO::PARAM_INT);
            $stmtOrden->execute();
            $orden = $stmtOrden->fetch(PDO::FETCH_ASSOC);

            if (!$orden) {
                return [
                    "estado" => 0,
                    "mensaje" => "Orden no encontrada"
                ];
            }

            // Actualizar estado de la orden
            $previous_state_id = 2; // ASIGNADO
            $new_state_id = 4; // RECHAZADA
            $action_type_id = 4; // RECHAZO

            $sql = "UPDATE service_orders
                    SET order_states_id = :new_state_id
                    WHERE id = :orden_id AND status = 1";

            $sentencia = $this->db->prepare($sql);
            $sentencia->bindParam(':new_state_id', $new_state_id, PDO::PARAM_INT);
            $sentencia->bindParam(':orden_id', $orden_id, PDO::PARAM_INT);
            $sentencia->execute();

            if ($sentencia->rowCount() > 0) {
                // Insertar en service_order_detail con trazabilidad
                $sqlDetail = "INSERT INTO service_order_detail
                             (service_order_id, previous_state_id, order_state_id, technician_id,
                              action_type_id, assigned_by, assigned_at, responded_at, response_note)
                             VALUES
                             (:service_order_id, :previous_state_id, :order_state_id, :technician_id,
                              :action_type_id, :assigned_by, NOW(), NOW(), :response_note)";

                $stmtDetail = $this->db->prepare($sqlDetail);
                $stmtDetail->bindParam(':service_order_id', $orden_id, PDO::PARAM_INT);
                $stmtDetail->bindParam(':previous_state_id', $previous_state_id, PDO::PARAM_INT);
                $stmtDetail->bindParam(':order_state_id', $new_state_id, PDO::PARAM_INT);
                $stmtDetail->bindParam(':technician_id', $orden['technician_id'], PDO::PARAM_INT);
                $stmtDetail->bindParam(':action_type_id', $action_type_id, PDO::PARAM_INT);
                $assigned_by = $orden['supervisor_id'];
                $stmtDetail->bindParam(':assigned_by', $assigned_by, PDO::PARAM_INT);
                $stmtDetail->bindParam(':response_note', $motivo_rechazo, PDO::PARAM_STR);
                $stmtDetail->execute();

                return [
                    "estado" => 1,
                    "mensaje" => "Orden rechazada correctamente"
                ];
            }

            return [
                "estado" => 0,
                "mensaje" => "No se pudo rechazar la orden"
            ];
        } catch (Exception $e) {
            return [
                "estado" => 0,
                "mensaje" => "Error: " . $e->getMessage()
            ];
        }
    }
}
