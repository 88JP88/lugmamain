<?php
    
    class validate_api_key {
        function validate_token($dta) {
            
            require_once '../../apiAuth/v1/model/modelSecurity/uuid/uuidd.php';
            require_once '../../apiAuth/v1/model/validations/validate_api_code.php';
            //require_once '../../apiAuth/v1/model/validations/post_new_code.php';
           
            $validate = new model_validate();
           $result= $validate->model_api_key($dta);
            if($result=="0"){
                return "0";
            }else{
               
           
            return $primeros_ocho;
            //$dta['uuid'] = $primeros_ocho;
            }
            
            
        }

       
    }
    
?>