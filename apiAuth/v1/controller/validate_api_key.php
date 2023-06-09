<?php
    
    class validate_api_key {
        function validate_token($dta) {
            
            require_once '../../apiAuth/v1/model/modelSecurity/uuid/uuidd.php';
            require_once '../../apiAuth/v1/model/validations/validate_api_key.php';
            require_once '../../apiAuth/v1/model/validations/post_new_code.php';
           
            $validate = new model_validate();
           $result= $validate->model_api_key($dta);
            if($result=="0"){
                return "0";
            }else{
                $gen_uuid = new generateUuid();
            $myuuid = $gen_uuid->guidv4();
            $primeros_ocho = substr($myuuid, 0, 8);
            $primeros_nueve = substr($myuuid, 0, 9);

            $data=[
                'profile_id' => $result,
                'code'=>$primeros_nueve.$myuuid."-".$primeros_ocho
            ];
            $codes=new model_post_code();
            $codes-> model_code($data);
            return $primeros_nueve.$myuuid."-".$primeros_ocho;
            //$dta['uuid'] = $primeros_ocho;
            }
            
            
        }

       
    }
    
?>