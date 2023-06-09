<?php
    
    class model_post_code {
        function model_code($dta) {
    
            require_once 'database/db_users.php'; // Asegúrate de proporcionar la ruta correcta al archivo de conexión a la base de datos
    
            // Realiza la conexión a la base de datos (reemplaza conn() con tu propia lógica de conexión)
            $conectar = conn();
    
            // Verifica si la conexión se realizó correctamente
            if (!$conectar) {
                return "Error de conexión a la base de datos";
            }
    
            // Escapa los valores para prevenir inyección SQL
            $escaped_uuid = mysqli_real_escape_string($conectar, $dta['profile_id']);
            $escaped_value = mysqli_real_escape_string($conectar,$dta['code']);
    
            // Realiza la consulta INSERT en la base de datos
            $query = mysqli_query($conectar, "UPDATE user_secrets SET gen_code='$escaped_value' where profile_id='$escaped_uuid'");
    
            if ($query) {
                return "true";
            } else {
                return "Error al insertar en la base de datos";
            }
        }
    }
    
?>