<?php
    
    class post_functions {
        function post_users($dta) {
            
            require_once '../../apiUsers/v1/model/modelSecurity/uuid/uuidd.php';
            require_once '../../apiUsers/v1/model/users/post_users.php';
           
    require_once '../../apiUsers/v1/model/modelSecurity/crypt/cryptic.php';

            $gen_uuid = new generateUuid();
            $myuuid = $gen_uuid->guidv4();
            $myuuid2 = $gen_uuid->guidv4();
            $myuuid3 = $gen_uuid->guidv4();
            $primeros_ocho = substr($myuuid, 0, 8);
            $primeros_ocho2 = substr($myuuid2, 0, 8);
            $primeros_ocho3 = substr($myuuid3, 0, 8);
            
           
            $dato_encriptado = $encriptar($dta['pass']);
            $dato_encriptado1 = $encriptar($dta['word']);


            $dta['primeros_ocho'] = $primeros_ocho;
            $dta['primeros_ocho2'] = $primeros_ocho2;
            $dta['primeros_ocho3'] = $primeros_ocho3;
            $dta['dato_encriptado'] = $dato_encriptado;
            $dta['dato_encriptado1'] = $dato_encriptado1;
            
           $model = new model_functions();
            return $model->model_user($dta);
        }

        function post_users_invite($dta) {
            
            require_once '../../apiUsers/v1/model/modelSecurity/uuid/uuidd.php';
            require_once '../../apiUsers/v1/model/users/post_users.php';
           
    require_once '../../apiUsers/v1/model/modelSecurity/crypt/cryptic.php';

            $gen_uuid = new generateUuid();
            $myuuid = $gen_uuid->guidv4();
            $myuuid2 = $gen_uuid->guidv4();
            $myuuid3 = $gen_uuid->guidv4();
            $primeros_ocho = substr($myuuid, 0, 8);
            $primeros_ocho2 = substr($myuuid2, 0, 8);
            $primeros_ocho3 = substr($myuuid3, 0, 8);
            
           
            $dato_encriptado = $encriptar($dta['pass']);


            $dta['primeros_ocho'] = $primeros_ocho;
            $dta['primeros_ocho2'] = $primeros_ocho2;
            $dta['primeros_ocho3'] = $primeros_ocho3;
            $dta['dato_encriptado'] = $dato_encriptado;
            
           $model = new model_functions();
            return $model->model_user_invite($dta);
        }

        function post_users_invite_post($dta) {
            
            require_once '../../apiUsers/v1/model/modelSecurity/uuid/uuidd.php';
            require_once '../../apiUsers/v1/model/users/post_users.php';
           
    require_once '../../apiUsers/v1/model/modelSecurity/crypt/cryptic.php';

            $gen_uuid = new generateUuid();
            $myuuid = $gen_uuid->guidv4();
            $myuuid2 = $gen_uuid->guidv4();
            $myuuid3 = $gen_uuid->guidv4();
            $primeros_ocho = substr($myuuid, 0, 8);
            $primeros_ocho2 = substr($myuuid2, 0, 8);
            $primeros_ocho3 = substr($myuuid3, 0, 8);
            
           
            $dato_encriptado = $encriptar($dta['pass']);


            $dta['primeros_ocho'] = $primeros_ocho;
            $dta['primeros_ocho2'] = $primeros_ocho2;
            $dta['primeros_ocho3'] = $primeros_ocho3;
            $dta['dato_encriptado'] = $dato_encriptado;
            
           $model = new model_functions();
            return $model->model_user_invite_post($dta);
        }
        function verify_sub($dta) {
            
            require_once '../../apiUsers/v1/model/modelSecurity/uuid/uuidd.php';
            require_once '../../apiUsers/v1/model/users/post_users.php';
           
    require_once '../../apiUsers/v1/model/modelSecurity/crypt/cryptic.php';

            $gen_uuid = new generateUuid();
            $myuuid = $gen_uuid->guidv4();
            $myuuid2 = $gen_uuid->guidv4();
            $myuuid3 = $gen_uuid->guidv4();
            $primeros_ocho = substr($myuuid, 0, 8);
            $primeros_ocho2 = substr($myuuid2, 0, 8);
            $primeros_ocho3 = substr($myuuid3, 0, 8);
            
           
            $dato_encriptado = $encriptar($dta['pass']);
            $dato_encriptado1 = $encriptar($dta['word']);


            $dta['primeros_ocho'] = $primeros_ocho;
            $dta['primeros_ocho2'] = $primeros_ocho2;
            $dta['primeros_ocho3'] = $primeros_ocho3;
            $dta['dato_encriptado'] = $dato_encriptado;
            $dta['dato_encriptado1'] = $dato_encriptado1;
            
           $model = new model_functions();
            return $model->model_user($dta);
        }

        
    }
    
?>