<?php
    
    class authenticator {
        function auth_code($data1) {

            require_once 'env/domain.php';


$sub_domaincon = new model_dom();
$sub_domain = $sub_domaincon->dom();
            $url = $sub_domain.'/lugmamain/apiAuth/v1/authCode/';
    
            $data = [
                'api_key' => $data1
            ];
    
            $curl = curl_init();
    
            // Configurar las opciones de la sesión cURL
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    
            // Ejecutar la solicitud y obtener la respuesta
            $response1 = curl_exec($curl);
    
            // Cerrar la sesión cURL
            curl_close($curl);
    
            return $response1;
        }
    }
    
    
?>