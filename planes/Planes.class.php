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
                                                cp.code_client_plan,
                                                so.code_service_orders,
                                                c.document_number,
                                                cp.client_id,
                                                concat(c.name_or_company_name, ' ', c.paternal_surname, ' ', c.maternal_surname) as client_name,
                                                cp.plan_id,
                                                p.name as plan_name,
                                                cp.invoice_type,
                                                cp.router_serial,
                                                cp.router_model,
                                                cp.ip_address,
                                                cp.mac_address,
                                                cp.wifi_ssid,
                                                cp.wifi_password,
                                                cp.service_status,
                                                cp.installed_at,
                                                cp.start_date,
                                                cp.end_date,
                                                cp.status,
                                                cp.plan_state_id,
                                                ps.code as plan_state_code,
                                                ps.name as plan_state_name
                                            from client_plans cp
                                            inner join clients c on cp.client_id = c.id
                                            inner join plans p on cp.plan_id = p.id
                                            left join service_orders so on cp.service_order_id = so.id
                                            left join client_plan_states ps on cp.plan_state_id = ps.id
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

    public function obtener_nodo()
    {
        try {
            $sql = "SELECT id, name FROM nodes WHERE status = 1";
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
    public function obtener_orden_code($code_order)
    {
        $stmt = $this->db->prepare("SELECT
                                so.id as order_id,
                                so.client_id,
                                concat(c.name_or_company_name, ' ', c.paternal_surname, ' ', c.maternal_surname) as client_name,
                                c.document_number,
                                so.plan_id,
                                    p.name
                                FROM service_orders so
                                inner join clients c on so.client_id = c.id
                                inner join plans p on so.plan_id = p.id
                                WHERE code_service_orders = :code_order");
        $stmt->bindParam(':code_order', $code_order, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function insertar_plan(
        $client_id,
        $plan_id,
        $nodo_id,
        $billing_day,
        $start_date,
        $created_by,
        $order_id,
        $invoice_type,
        $router_serial,
        $router_model,
        $ip_address,
        $mac_address,
        $wifi_ssid,
        $wifi_password,
        $installed_at
    ) {
        try {
            $this->db->beginTransaction();

            $stmt = $this->db->prepare("INSERT INTO client_plans
                (client_id, plan_id, node_id, billing_day, start_date, created_by, service_order_id,
                 invoice_type, router_serial, router_model, ip_address, mac_address, wifi_ssid,
                 wifi_password, installed_at, plan_state_id, status)
                VALUES
                (:client_id, :plan_id, :nodo_id, :billing_day, :start_date, :created_by, :order_id,
                 :invoice_type, :router_serial, :router_model, :ip_address, :mac_address, :wifi_ssid,
                 :wifi_password, :installed_at, 1, 1)");

            $stmt->bindParam(':client_id', $client_id);
            $stmt->bindParam(':plan_id', $plan_id);
            $stmt->bindParam(':nodo_id', $nodo_id);
            $stmt->bindParam(':billing_day', $billing_day);
            $stmt->bindParam(':start_date', $start_date);
            $stmt->bindParam(':created_by', $created_by);
            $stmt->bindParam(':order_id', $order_id);
            $stmt->bindParam(':invoice_type', $invoice_type);
            $stmt->bindParam(':router_serial', $router_serial);
            $stmt->bindParam(':router_model', $router_model);
            $stmt->bindParam(':ip_address', $ip_address);
            $stmt->bindParam(':mac_address', $mac_address);
            $stmt->bindParam(':wifi_ssid', $wifi_ssid);
            $stmt->bindParam(':wifi_password', $wifi_password);
            $stmt->bindParam(':installed_at', $installed_at);

            if ($stmt->execute()) {
            // Plan insertado con plan_state_id = 1 (PENDIENTE) por defecto
            // Ya NO actualizamos service_orders aquí (separación de responsabilidades)
            $this->db->commit();

            return [
                'estado' => 1,
                'mensaje' => 'Plan insertado correctamente. Pendiente de aprobación.'
            ];
            } else {
                $this->db->rollBack();
                return [
                    'estado' => 0,
                    'mensaje' => 'Error al insertar el plan'
                ];
            }
        } catch (Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    public function obtener_por_id($id)
    {
        try {
            $stmt = $this->db->prepare("SELECT cp.service_order_id as order_id,
                                        so.code_service_orders as code_orders,
                                        cp.client_id,
                                        concat(c.name_or_company_name, ' ', c.paternal_surname, ' ', c.maternal_surname) as client_name,
                                        c.document_number,
                                        cp.plan_id,
                                        p.name as plan_name,
                                        cp.node_id,
                                        n.name as node_name,
                                        cp.billing_day,
                                        cp.start_date,
                                        cp.installed_at,
                                        cp.invoice_type,
                                        cp.router_serial,
                                        cp.router_model,
                                        cp.ip_address,
                                        cp.mac_address,
                                        cp.wifi_ssid,
                                        cp.wifi_password
                                 from client_plans cp
                                inner join service_orders so on cp.service_order_id=so.id
                                inner join clients c on cp.client_id=c.id
                                inner join plans p on cp.plan_id = p.id
                                inner join nodes n on cp.node_id=n.id
                                where cp.id = :id");
            
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                return $stmt->fetch(PDO::FETCH_ASSOC);
            }

            return null;
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function actualizar_plan(
        $id,
        $client_id,
        $plan_id,
        $nodo_id,
        $billing_day,
        $start_date,
        $updated_by,
        $invoice_type,
        $router_serial,
        $router_model,
        $ip_address,
        $mac_address,
        $wifi_ssid,
        $wifi_password,
        $installed_at
    ) {
        try {
            $stmt = $this->db->prepare("UPDATE client_plans SET 
                client_id = :client_id,
                plan_id = :plan_id,
                node_id = :nodo_id,
                billing_day = :billing_day,
                start_date = :start_date,
                updated_by = :updated_by,
                invoice_type = :invoice_type,
                router_serial = :router_serial,
                router_model = :router_model,
                ip_address = :ip_address,
                mac_address = :mac_address,
                wifi_ssid = :wifi_ssid,
                wifi_password = :wifi_password,
                installed_at = :installed_at
                WHERE id = :id");

            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':client_id', $client_id);
            $stmt->bindParam(':plan_id', $plan_id);
            $stmt->bindParam(':nodo_id', $nodo_id);
            $stmt->bindParam(':billing_day', $billing_day);
            $stmt->bindParam(':start_date', $start_date);
            $stmt->bindParam(':updated_by', $updated_by);
            $stmt->bindParam(':invoice_type', $invoice_type);
            $stmt->bindParam(':router_serial', $router_serial);
            $stmt->bindParam(':router_model', $router_model);
            $stmt->bindParam(':ip_address', $ip_address);
            $stmt->bindParam(':mac_address', $mac_address);
            $stmt->bindParam(':wifi_ssid', $wifi_ssid);
            $stmt->bindParam(':wifi_password', $wifi_password);
            $stmt->bindParam(':installed_at', $installed_at);

            return $stmt->execute();
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function eliminar_plan($id)
    {
        try {
            $stmt = $this->db->prepare("UPDATE client_plans SET status = 0 WHERE id = :id");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function aprobar_plan($id)
    {
        try {
            // Obtener el ID de la orden asociada al plan
            $stmtGet = $this->db->prepare("SELECT service_order_id FROM client_plans WHERE id = :id");
            $stmtGet->bindParam(':id', $id, PDO::PARAM_INT);
            $stmtGet->execute();
            $plan = $stmtGet->fetch(PDO::FETCH_ASSOC);

            if (!$plan || !$plan['service_order_id']) {
                return false; // Plan no encontrado o sin orden asociada
            }

            // Actualizar estado del plan a APROBADO (plan_state_id = 2)
        $stmt = $this->db->prepare("UPDATE client_plans SET plan_state_id = 2 WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        
        if ($stmt->execute()) {
            // Plan aprobado, ya NO actualizamos service_orders (separación de responsabilidades)
            return true;
        }
            return false;
        } catch (Exception $e) {
            throw $e;
        }
    }
}
