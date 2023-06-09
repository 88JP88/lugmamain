<?php
    
    class model_validate {
        function model_api_key($dta) {
            require_once 'database/db_users.php';
            $conectar = conn();
        
            if (!$conectar) {
                return "Error de conexión a la base de datos";
            }
        
            $escaped_dta = mysqli_real_escape_string($conectar, $dta);
        
            $query = mysqli_query($conectar, "SELECT profile_id FROM user_secrets WHERE gen_code = '$dta'");
        
            if ($query) {
                $result = mysqli_fetch_assoc($query);
                if ($result) {
                    return $result['profile_id'];
                } else {
                    return "0";//ningun elemento
                }
            } else {
                return "Error en la consulta";
            }
        }
    }
    
    
?>