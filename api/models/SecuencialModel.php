<?php

require_once "connection.php";
class SecuencialModel
{

    public function obtenerProximoNumero($oficina, $caja, $id_office)
    {

        try {
            $link = Connection::connect();
            $link->beginTransaction();

            // Agregamos la condición del filtro id_office
            $sql = "SELECT * FROM secuencials 
                    WHERE oficina_secuencial = :oficina 
                    AND caja_secuencial = :caja 
                    AND office_secuencial = :id_office 
                    FOR UPDATE";
            $stmt = $link->prepare($sql);
            $stmt->bindParam(":oficina", $oficina, PDO::PARAM_INT);
            $stmt->bindParam(":caja", $caja, PDO::PARAM_INT);
            $stmt->bindParam(":id_office", $id_office, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$result) {
                // No existe, creamos el registro con secuencial 1
                $sqlInsert = "INSERT INTO secuencials 
                              (oficina_secuencial, caja_secuencial, office_secuencial, ultimo_numero_secuencial) 
                              VALUES (:oficina, :caja, :id_office, 1)";
                $stmtInsert = $link->prepare($sqlInsert);
                $stmtInsert->bindParam(":oficina", $oficina, PDO::PARAM_INT);
                $stmtInsert->bindParam(":caja", $caja, PDO::PARAM_INT);
                $stmtInsert->bindParam(":id_office", $id_office, PDO::PARAM_INT);
                $stmtInsert->execute();
                $nuevoSecuencial = 1;
            } else {
                // Existe, incrementamos el secuencial
                $nuevoSecuencial = $result["ultimo_numero_secuencial"] + 1;
                $sqlUpdate = "UPDATE secuencials 
                              SET ultimo_numero_secuencial = :num 
                              WHERE id_secuencial = :id";
                $stmtUpdate = $link->prepare($sqlUpdate);
                $stmtUpdate->bindParam(":num", $nuevoSecuencial, PDO::PARAM_INT);
                $stmtUpdate->bindParam(":id", $result["id_secuencial"], PDO::PARAM_INT);
                $stmtUpdate->execute();
            }

            $link->commit();
            return $nuevoSecuencial;
        } catch (Exception $e) {
            $link->rollBack();
            return false;
        }
    }
}
