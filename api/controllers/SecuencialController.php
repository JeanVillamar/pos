<?php

require_once "models/SecuencialModel.php";


class SecuencialController {

    static public function getNuevoSecuencial($data){

        $secuencialModel = new SecuencialModel();
        $nuevoNumero = $secuencialModel->obtenerProximoNumero($data["oficina_secuencial"], $data["caja_secuencial"], $data["office_secuencial"]);

        if(!$nuevoNumero){
            // Error o algo
            return [
                "status" => 400,
                "results" => "No se pudo obtener/crear el secuencial"
            ];
        }

        return [
            "status" => 200,
            "results" => $nuevoNumero
        ];
    }
}



