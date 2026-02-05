<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/fibra-nort/core/Database.php';

class Gestion
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    // --- MÉTODOS DE ÓRDENES ---

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
            return $sentencia->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function obtener_cliente_dni($document_number)
    {
        try {
            $sql = "SELECT id, name_or_company_name, paternal_surname, maternal_surname, date_birth       
                    FROM clients 
                    WHERE document_number = :document_number AND status = 1";
            $sentencia = $this->db->prepare($sql);
            $sentencia->bindParam(':document_number', $document_number);
            $sentencia->execute();
            return $sentencia->fetch(PDO::FETCH_ASSOC);
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
                $orden_id = $this->db->lastInsertId();
                
                // Registro inicial en trazabilidad
                $sqlDetail = "INSERT INTO service_order_detail 
                               (service_order_id, previous_state_id, order_state_id, 
                                technician_id, action_type_id, assigned_by, assigned_at)
                               VALUES 
                               (:orden_id, NULL, 1, NULL, 1, :created_by, NOW())";
                
                $stmtDetail = $this->db->prepare($sqlDetail);
                $stmtDetail->bindParam(':orden_id', $orden_id, PDO::PARAM_INT);
                $stmtDetail->bindParam(':created_by', $created_by, PDO::PARAM_INT);
                $stmtDetail->execute();
                
                return ['estado' => 1, 'mensaje' => 'Orden insertada correctamente', 'data' => ['orden_id' => $orden_id]];
            }
            return ['estado' => 0, 'mensaje' => 'Error al insertar la orden'];
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function editar($id, $technician_id, $client_id, $scheduled_date, $scheduled_time, $plan_id, $description, $updated_by)
    {
        try {
            $sql = "UPDATE service_orders 
                    SET technician_id = :technician_id, client_id = :client_id, 
                        scheduled_date = :scheduled_date, scheduled_time = :scheduled_time, 
                        plan_id = :plan_id, description = :description, updated_by = :updated_by 
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
                return ['estado' => 1, 'mensaje' => 'Orden actualizada correctamente'];
            }
            return ['estado' => 0, 'mensaje' => 'Error al actualizar la orden'];
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
            return $stmt->execute();
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function obtener_por_id($id)
    {
        try {
            $sql = "SELECT o.*, c.name_or_company_name, c.paternal_surname, c.maternal_surname, c.document_number, p.name AS plan_name
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

    // --- MÉTODOS DE ASIGNACIÓN ---

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
                return ["estado" => 1, "mensaje" => "exito", "data" => $sentencia->fetchAll(PDO::FETCH_ASSOC)];
            }
            return ["estado" => 0, "mensaje" => "No hay asignaciones", "data" => ""];
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function listar_tecnicos()
    {
        try {
            $sql = "SELECT id, name, email FROM users WHERE role_id = 3 AND status = 1 ORDER BY name ASC";
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
            $stmtGet = $this->db->prepare("SELECT order_states_id FROM service_orders WHERE id = :orden_id");
            $stmtGet->bindParam(':orden_id', $orden_id, PDO::PARAM_INT);
            $stmtGet->execute();
            $orden = $stmtGet->fetch(PDO::FETCH_ASSOC);
            
            if (!$orden) return ["estado" => 0, "mensaje" => "Orden no encontrada"];
            
            $previous_state_id = $orden['order_states_id'];
            $new_state_id = 2; // ASIGNADO
            $supervisor_id = $_SESSION['user_id'];
            
            $sql = "UPDATE service_orders 
                    SET technician_id = :technician_id, order_states_id = :new_state_id, supervisor_id = :supervisor_id
                    WHERE id = :orden_id AND status = 1";
            $sentencia = $this->db->prepare($sql);
            $sentencia->bindParam(':technician_id', $technician_id);
            $sentencia->bindParam(':new_state_id', $new_state_id);
            $sentencia->bindParam(':orden_id', $orden_id);
            $sentencia->bindParam(':supervisor_id', $supervisor_id);
            
            if ($sentencia->execute()) {
                $sqlDetail = "INSERT INTO service_order_detail 
                               (service_order_id, previous_state_id, order_state_id, technician_id, action_type_id, assigned_by, assigned_at)
                               VALUES (:orden_id, :previous_state_id, :new_state_id, :technician_id, 2, :assigned_by, NOW())";
                $stmtDetail = $this->db->prepare($sqlDetail);
                $stmtDetail->bindParam(':orden_id', $orden_id);
                $stmtDetail->bindParam(':previous_state_id', $previous_state_id);
                $stmtDetail->bindParam(':new_state_id', $new_state_id);
                $stmtDetail->bindParam(':technician_id', $technician_id);
                $stmtDetail->bindParam(':assigned_by', $supervisor_id);
                $stmtDetail->execute();
                return ["estado" => 1, "mensaje" => "Técnico asignado correctamente"];
            }
            return ["estado" => 0, "mensaje" => "No se pudo asignar"];
        } catch (Exception $e) {
            return ["estado" => 0, "mensaje" => "Error: " . $e->getMessage()];
        }
    }

    public function aceptar_orden($orden_id)
    {
        try {
            $sqlOrden = "SELECT technician_id, supervisor_id FROM service_orders WHERE id = :orden_id AND status = 1";
            $stmtOrden = $this->db->prepare($sqlOrden);
            $stmtOrden->bindParam(':orden_id', $orden_id);
            $stmtOrden->execute();
            $orden = $stmtOrden->fetch(PDO::FETCH_ASSOC);
            if (!$orden) return ["estado" => 0, "mensaje" => "Orden no encontrada"];

            $new_state_id = 3; // ACEPTADA
            $sql = "UPDATE service_orders SET order_states_id = :new_state_id WHERE id = :orden_id";
            $sentencia = $this->db->prepare($sql);
            $sentencia->bindParam(':new_state_id', $new_state_id);
            $sentencia->bindParam(':orden_id', $orden_id);
            
            if ($sentencia->execute()) {
                $sqlDetail = "INSERT INTO service_order_detail (service_order_id, previous_state_id, order_state_id, technician_id, action_type_id, assigned_by, assigned_at, responded_at, response_note)
                             VALUES (:orden_id, 2, :new_state_id, :technician_id, 3, :assigned_by, NOW(), NOW(), 'Orden aceptada por el técnico')";
                $stmtDetail = $this->db->prepare($sqlDetail);
                $stmtDetail->bindParam(':orden_id', $orden_id);
                $stmtDetail->bindParam(':new_state_id', $new_state_id);
                $stmtDetail->bindParam(':technician_id', $orden['technician_id']);
                $stmtDetail->bindParam(':assigned_by', $orden['supervisor_id']);
                $stmtDetail->execute();
                return ["estado" => 1, "mensaje" => "Orden aceptada correctamente"];
            }
            return ["estado" => 0, "mensaje" => "No se pudo aceptar"];
        } catch (Exception $e) {
            return ["estado" => 0, "mensaje" => "Error: " . $e->getMessage()];
        }
    }

    public function rechazar_orden($orden_id, $motivo_rechazo)
    {
        try {
            $sqlOrden = "SELECT technician_id, supervisor_id FROM service_orders WHERE id = :orden_id AND status = 1";
            $stmtOrden = $this->db->prepare($sqlOrden);
            $stmtOrden->bindParam(':orden_id', $orden_id);
            $stmtOrden->execute();
            $orden = $stmtOrden->fetch(PDO::FETCH_ASSOC);
            if (!$orden) return ["estado" => 0, "mensaje" => "Orden no encontrada"];

            $new_state_id = 4; // RECHAZADA
            $sql = "UPDATE service_orders SET order_states_id = :new_state_id WHERE id = :orden_id";
            $sentencia = $this->db->prepare($sql);
            $sentencia->bindParam(':new_state_id', $new_state_id);
            $sentencia->bindParam(':orden_id', $orden_id);

            if ($sentencia->execute()) {
                $sqlDetail = "INSERT INTO service_order_detail (service_order_id, previous_state_id, order_state_id, technician_id, action_type_id, assigned_by, assigned_at, responded_at, response_note)
                             VALUES (:orden_id, 2, :new_state_id, :technician_id, 4, :assigned_by, NOW(), NOW(), :note)";
                $stmtDetail = $this->db->prepare($sqlDetail);
                $stmtDetail->bindParam(':orden_id', $orden_id);
                $stmtDetail->bindParam(':new_state_id', $new_state_id);
                $stmtDetail->bindParam(':technician_id', $orden['technician_id']);
                $stmtDetail->bindParam(':assigned_by', $orden['supervisor_id']);
                $stmtDetail->bindParam(':note', $motivo_rechazo);
                $stmtDetail->execute();
                return ["estado" => 1, "mensaje" => "Orden rechazada correctamente"];
            }
            return ["estado" => 0, "mensaje" => "No se pudo rechazar"];
        } catch (Exception $e) {
            return ["estado" => 0, "mensaje" => "Error: " . $e->getMessage()];
        }
    }
}
