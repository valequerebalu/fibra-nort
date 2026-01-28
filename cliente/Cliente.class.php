<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/fibra-nort/core/Database.php';

class Cliente
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function listar_clientes()
    {
        try {
            // Seleccionamos las columnas exactas de tu tabla
            $sql = "SELECT 
                        id, 
                        code_clients, 
                        full_name, 
                        document_number, 
                        phone, 
                        email, 
                        address,
                        reference,
                        client_state_id as status
                    FROM clients 
                    WHERE status = 1";

            $sentencia = $this->db->prepare($sql);
            $sentencia->execute();

            if ($sentencia->rowCount() > 0) {
                return [
                    "estado" => 1,
                    "mensaje" => "exito",
                    "data" => $sentencia->fetchAll(PDO::FETCH_ASSOC)
                ];
            }

            return ["estado" => 0, "mensaje" => "No hay clientes registrados", "data" => ""];
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function obtener_por_id($id)
    {
        try {
            $sql = "SELECT 
                        id,
                        document_type_id,
                        document_number,
                        full_name,
                        phone,
                        email,
                        address,
                        reference,
                        client_state_id as status
                    FROM clients
                    WHERE id = :id";
            
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return null;
        }
    }

    public function obtener_tipos_documento()
    {
        try {
            // Nota: si la columna de estado no se llama "status", quita el filtro o ajusta el nombre
            $sql = "SELECT id, code FROM document_types WHERE status = 1";

            // Si no devuelve resultados, intenta sin filtro de estado
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            $tipos = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (empty($tipos)) {
                $sql = "SELECT id, code FROM document_types";
                $stmt = $this->db->prepare($sql);
                $stmt->execute();
                $tipos = $stmt->fetchAll(PDO::FETCH_ASSOC);
            }

            return $tipos;
        } catch (Exception $e) {
            return [];
        }
    }
    public function insertar($doc_type_id, $doc_num, $full_name, $phone, $email, $address, $reference, $state_id, $created_by)
    {
        $this->db->beginTransaction();
        try {
            // El code_clients NO se incluye porque el TRIGGER lo genera solo
            $sql = "INSERT INTO clients (
                        document_type_id, document_number, full_name, 
                        phone, email, address, reference, 
                        client_state_id, created_by
                    ) VALUES (
                        :doc_type, :doc_num, :name, 
                        :phone, :email, :address, :ref, 
                        :state, :user_id
                    )";

            $sentencia = $this->db->prepare($sql);
            $sentencia->bindParam(":doc_type", $doc_type_id, PDO::PARAM_INT);
            $sentencia->bindParam(":doc_num", $doc_num, PDO::PARAM_STR);
            $sentencia->bindParam(":name", $full_name, PDO::PARAM_STR);
            $sentencia->bindParam(":phone", $phone, PDO::PARAM_STR);
            $sentencia->bindParam(":email", $email, PDO::PARAM_STR);
            $sentencia->bindParam(":address", $address, PDO::PARAM_STR);
            $sentencia->bindParam(":ref", $reference, PDO::PARAM_STR);
            $sentencia->bindParam(":state", $state_id, PDO::PARAM_INT);
            $sentencia->bindParam(":user_id", $created_by, PDO::PARAM_INT);

            $result = $sentencia->execute();
            $this->db->commit();

            return $result;
        } catch (Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    public function editar($id, $doc_type_id, $doc_num, $full_name, $phone, $email, $address, $reference, $state_id)
    {
        $this->db->beginTransaction();
        try {
            $sql = "UPDATE clients SET 
                        document_type_id = :doc_type, 
                        document_number = :doc_num, 
                        full_name = :name, 
                        phone = :phone, 
                        email = :email, 
                        address = :address, 
                        reference = :ref, 
                        client_state_id = :state,
                        updated_at = NOW()
                    WHERE id = :id";

            $sentencia = $this->db->prepare($sql);
            $sentencia->bindParam(":id", $id, PDO::PARAM_INT);
            $sentencia->bindParam(":doc_type", $doc_type_id, PDO::PARAM_INT);
            $sentencia->bindParam(":doc_num", $doc_num, PDO::PARAM_STR);
            $sentencia->bindParam(":name", $full_name, PDO::PARAM_STR);
            $sentencia->bindParam(":phone", $phone, PDO::PARAM_STR);
            $sentencia->bindParam(":email", $email, PDO::PARAM_STR);
            $sentencia->bindParam(":address", $address, PDO::PARAM_STR);
            $sentencia->bindParam(":ref", $reference, PDO::PARAM_STR);
            $sentencia->bindParam(":state", $state_id, PDO::PARAM_INT);

            $result = $sentencia->execute();
            $this->db->commit();

            return $result;
        } catch (Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    public function eliminar($id)
    {
        $this->db->beginTransaction();
        try {
            $sql = "UPDATE clients SET status = 0 WHERE id = :id";

            $sentencia = $this->db->prepare($sql);
            $sentencia->bindParam(":id", $id, PDO::PARAM_INT);

            $result = $sentencia->execute();
            $this->db->commit();

            return $result;
        } catch (Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }
}
