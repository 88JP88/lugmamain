<?php
    
    class model_functions {
        function model_user($dta) {
    
            require_once 'database/db_users.php'; // Asegúrate de proporcionar la ruta correcta al archivo de conexión a la base de datos
    
            // Realiza la conexión a la base de datos (reemplaza conn() con tu propia lógica de conexión)
            $conectar = conn();
    
            // Verifica si la conexión se realizó correctamente
            if (!$conectar) {
                return "Error de conexión a la base de datos";
            }
    
            // Escapa los valores para prevenir inyección SQL
            $user = mysqli_real_escape_string($conectar, $dta['user']);
            $name = mysqli_real_escape_string($conectar, $dta['name']);
            $last_name = mysqli_real_escape_string($conectar, $dta['last_name']);
            $contact = mysqli_real_escape_string($conectar, $dta['contact']);
            $dato_encriptado = mysqli_real_escape_string($conectar, $dta['dato_encriptado']);
            $escaped_value = mysqli_real_escape_string($conectar, $dta['dato_encriptado']);
            $rol = mysqli_real_escape_string($conectar, $dta['rol']);
            $type = mysqli_real_escape_string($conectar, $dta['subscription']);
            $code = mysqli_real_escape_string($conectar, $dta['code']);
            $dato_encriptado1 = mysqli_real_escape_string($conectar, $dta['dato_encriptado1']);
            $primeros_ocho = mysqli_real_escape_string($conectar, $dta['primeros_ocho']);
            $primeros_ocho2 = mysqli_real_escape_string($conectar, $dta['primeros_ocho2']);
            $primeros_ocho3 = mysqli_real_escape_string($conectar, $dta['primeros_ocho3']);
    
            $query= mysqli_query($conectar,"SELECT username FROM users where username='$user'");
            $nr=mysqli_num_rows($query);
        
            if($nr>=1){
                $info=[
        
                    'data' => "ups! el nombrede usuario está repetido , intenta nuevamente, gracias."
                    
                ];
             echo json_encode(['info'=>$info]);
             //echo "ups! el id del repo está repetido , intenta nuevamente, gracias.";
            }else{
        
              
                $query= mysqli_query($conectar,"SELECT sub_id FROM subscriptionlist where sub_id='$code' and secret_word='$dato_encriptado1' and is_active=0");
                $nr=mysqli_num_rows($query);
            
                if($nr<=0){
                    $info=[
            
                        'data' => "ups! el codigo o la palabra clave son erroneas , intenta nuevamente, gracias."
                        
                    ];
                 echo json_encode(['info'=>$info]);
                 //echo "ups! el id del repo está repetido , intenta nuevamente, gracias.";
                }else{
                    if (strpos($user, " ") === false && strlen($user) < 13 && preg_match('/^[^@#%&,:ñÑ]+$/', $user)) {
                                  
        
                        if (preg_match('/^(?=.*\d)(?=.*[A-Z])(?=.*[a-z])(?=.*[^\w\d\s:])([^\s]){8,}$/', $dato_encriptado) && strlen($user) > 4 && $dato_encriptado==$dato_encriptado)  {
                            $userm=$user."@lugma.tech";
                            $query= mysqli_query($conectar,"INSERT INTO users (username,name,last_name,contact,keyword,user_id,mail) VALUES ('$user','$name','$last_name','$contact','$dato_encriptado','$primeros_ocho','$userm')");
                                    
        
                            $query= mysqli_query($conectar,"SELECT rate_day FROM subscriptions where sub_id='$type'");
                            
            
                            $subs=[];
            
                            while($row = $query->fetch_assoc())
                            {
                                    $sub=[
                                        'rate' => $row['rate_day']
                                    ];
                                    
                                    array_push($subs,$sub);
                                    
                            }
                            $row=$query->fetch_assoc();
            
                            $response= json_encode(['subs'=>$subs]);
                            $fechaActual = date("Y-m-d");
                            $data = json_decode($response);
                            foreach ($data->subs as $character) {
                            
                                $resultado =  $character->rate;
            
                                $query1= mysqli_query($conectar,"INSERT INTO profiles (profile_id,user_id,rol,imageUrl,sub_id,sub_days,sub_date) VALUES ('$primeros_ocho2','$primeros_ocho','$rol','$name','$type','$resultado','$fechaActual')");
                                
        
                                if($rol=="student")
                                {
                                    $query1= mysqli_query($conectar,"INSERT INTO students (student_id,profile_id,name,last_name,invite,type) VALUES ('$primeros_ocho3','$primeros_ocho2','$name','$last_name',0,'signin')");
                                
        
                                }
                                if($rol=="teacher")
                                {
                                    $query1= mysqli_query($conectar,"INSERT INTO teachers (teacher_id,profile_id,name,last_name,invite,type) VALUES ('$primeros_ocho3','$primeros_ocho2','$name','$last_name',0,'signin')");
                                
        
                                }
                                if($rol=="coordinate")
                                {
                                    $query1= mysqli_query($conectar,"INSERT INTO coordinates (coor_id,profile_id,name,last_name,invite,type) VALUES ('$primeros_ocho3','$primeros_ocho2','$name','$last_name',0,'signin')");
                                
        
                                }
                                if($rol=="api_user")
                                {
                                    $query1= mysqli_query($conectar,"INSERT INTO api_users (user_id,profile_id,name,last_name,invite,type) VALUES ('$primeros_ocho3','$primeros_ocho2','$name','$last_name',0,'signin')");
                                
        
                                }
                            }
                            require_once 'env/domain.php';
                            $sub_domaincon=new model_dom;
                            $sub_domain=$sub_domaincon->dom();
                            $url = $sub_domain.'/lugmaTools/apiTools/v1/postSchedule/';
        
                      // Definir los datos a enviar en la solicitud POST
                      $data = array(
                          'user_id' => $primeros_ocho
                          );
                      $curl = curl_init();
                      
                      // Configurar las opciones de la sesión cURL
                      curl_setopt($curl, CURLOPT_URL, $url);
                      curl_setopt($curl, CURLOPT_POST, true);
                      curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                      curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                     // curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
                      
                      // Ejecutar la solicitud y obtener la respuesta
                      $response1 = curl_exec($curl);
                      //var_dump($data);
                      // Cerrar la sesión cURL
                      curl_close($curl);


                      $url = $sub_domain.'/lugmaresources/apiResources/v1/postSignIn/';
        
                      // Definir los datos a enviar en la solicitud POST
                      $data = array(
                          'resource_id' => $primeros_ocho3,
                          'profile_id' => $primeros_ocho2,
                          'name' => $dta['name'],
                          'last_name' => $dta['last_name'],
                          'value' => $dta['rol']
                          );
                      $curl = curl_init();
                      
                      // Configurar las opciones de la sesión cURL
                      curl_setopt($curl, CURLOPT_URL, $url);
                      curl_setopt($curl, CURLOPT_POST, true);
                      curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                      curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                     // curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
                      
                      // Ejecutar la solicitud y obtener la respuesta
                      $response1 = curl_exec($curl);
                      //var_dump($data);
                      // Cerrar la sesión cURL
                      curl_close($curl);
        //echo $response1;
        
                            //echo $username;
                            //echo $primeros_ocho;
                            echo "true"; // muestra "/mi-pagina.php?id=123"
            
            
                            //echo "La cadena contiene números, letras mayúsculas, minúsculas y símbolos";
                        } else {
                            echo "La contraseña debe contener minimo 8 caracteres (mayusculas*,minusculas*,numeros*,simbolos*) o las contraseñas no coinciden";
                        }
        
                       
                        
                    } else {
                        echo "usuario con espacios o cadena de texto mayor a 12 caracteres";
                    }
                                  
                                    }
                                }
            if ($query) {
                return "true";
            } else {
                return "Error al insertar en la base de datos";
            }
        }


        function model_user_invite($dta) {
    
            require_once 'database/db_users.php'; // Asegúrate de proporcionar la ruta correcta al archivo de conexión a la base de datos
    
            // Realiza la conexión a la base de datos (reemplaza conn() con tu propia lógica de conexión)
            $conectar = conn();
    
            // Verifica si la conexión se realizó correctamente
            if (!$conectar) {
                return "Error de conexión a la base de datos";
            }
    
            // Escapa los valores para prevenir inyección SQL
            $user = mysqli_real_escape_string($conectar, $dta['user']);
            $name = mysqli_real_escape_string($conectar, $dta['name']);
            $last_name = mysqli_real_escape_string($conectar, $dta['last_name']);
            $contact = mysqli_real_escape_string($conectar, $dta['contact']);
            $dato_encriptado = mysqli_real_escape_string($conectar, $dta['dato_encriptado']);
            $escaped_value = mysqli_real_escape_string($conectar, $dta['dato_encriptado']);
            $rol = mysqli_real_escape_string($conectar, $dta['rol']);
            $primeros_ocho = mysqli_real_escape_string($conectar, $dta['primeros_ocho']);
            $primeros_ocho2 = mysqli_real_escape_string($conectar, $dta['primeros_ocho2']);
            $primeros_ocho3 = mysqli_real_escape_string($conectar, $dta['primeros_ocho3']);
            $profile_id = mysqli_real_escape_string($conectar, $dta['profile_id']);
    
            $query= mysqli_query($conectar,"SELECT username FROM users where username='$user'");
            $nr=mysqli_num_rows($query);
        
            if($nr>=1){
                $info=[
        
                    'data' => "ups! el nombrede usuario está repetido , intenta nuevamente, gracias."
                    
                ];
             echo json_encode(['info'=>$info]);
             //echo "ups! el id del repo está repetido , intenta nuevamente, gracias.";
            }else{
        
              
                $query= mysqli_query($conectar,"SELECT sub_days FROM profiles where profile_id='$profile_id'");
                

                $days=[];
            
                while($row = $query->fetch_assoc())
                {
                        $day=[
                            'days' => $row['sub_days']
                        ];
                        
                        array_push($days,$day);
                        
                }
                $row=$query->fetch_assoc();

                $response= json_encode(['days'=>$days]);

                $fechaActual = date("Y-m-d");
                $data = json_decode($response);

                foreach ($data->days as $character) {
                
                    $daysresult =  $character->days;
                
            
               
                    if (strpos($user, " ") === false && strlen($user) < 13 && preg_match('/^[^@#%&,:ñÑ]+$/', $user)) {
                                  
        
                        if (preg_match('/^(?=.*\d)(?=.*[A-Z])(?=.*[a-z])(?=.*[^\w\d\s:])([^\s]){8,}$/', $dato_encriptado) && strlen($user) > 4 && $dato_encriptado==$dato_encriptado)  {
                            $userm=$user."@lugma.tech";
                            $query= mysqli_query($conectar,"INSERT INTO users (username,name,last_name,contact,keyword,user_id,mail) VALUES ('$user','$name','$last_name','$contact','$dato_encriptado','$primeros_ocho','$userm')");
                                    
        
                            
                            
                               
            
                                $query1= mysqli_query($conectar,"INSERT INTO profiles (profile_id,user_id,rol,imageUrl,sub_id,sub_days,sub_date) VALUES ('$primeros_ocho2','$primeros_ocho','$rol','$name','invite','$daysresult','$fechaActual')");
                                
        
                                if($rol=="student")
                                {
                                    $query1= mysqli_query($conectar,"INSERT INTO students (student_id,profile_id,name,last_name,invite,type) VALUES ('$primeros_ocho3','$primeros_ocho2','$name','$last_name',0,'invite')");
                                
        
                                }
                                if($rol=="teacher")
                                {
                                    $query1= mysqli_query($conectar,"INSERT INTO teachers (teacher_id,profile_id,name,last_name,invite,type) VALUES ('$primeros_ocho3','$primeros_ocho2','$name','$last_name',0,'invite')");
                                
        
                                }
                                if($rol=="coordinate")
                                {
                                    $query1= mysqli_query($conectar,"INSERT INTO coordinates (coor_id,profile_id,name,last_name,invite,type) VALUES ('$primeros_ocho3','$primeros_ocho2','$name','$last_name',0,'invite')");
                                
        
                                }
                                if($rol=="api_user")
                                {
                                    $query1= mysqli_query($conectar,"INSERT INTO api_users (user_id,profile_id,name,last_name,invite,type) VALUES ('$primeros_ocho3','$primeros_ocho2','$name','$last_name',0,'invite')");
                                
        
                                }
                            
                                require_once 'env/domain.php';
                                    $sub_domaincon=new model_dom;
                                    $sub_domain=$sub_domaincon->dom();
                                    $url = $sub_domain.'/lugmaTools/apiTools/v1/postSchedule/';
                
                              // Definir los datos a enviar en la solicitud POST
                              $data = array(
                                  'user_id' => $primeros_ocho
                                  );
                              $curl = curl_init();
                              
                              // Configurar las opciones de la sesión cURL
                              curl_setopt($curl, CURLOPT_URL, $url);
                              curl_setopt($curl, CURLOPT_POST, true);
                              curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                              curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                             // curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
                              
                              // Ejecutar la solicitud y obtener la respuesta
                              $response1 = curl_exec($curl);
                              //var_dump($data);
                              // Cerrar la sesión cURL
                              curl_close($curl);

                            //echo $username;
                            //echo $primeros_ocho;
                            return $primeros_ocho2." ".$primeros_ocho3; // muestra "/mi-pagina.php?id=123"
            //echo $response;
            
                            //echo "La cadena contiene números, letras mayúsculas, minúsculas y símbolos";
                        } else {
                            echo "La contraseña debe contener minimo 8 caracteres (mayusculas*,minusculas*,numeros*,simbolos*) o las contraseñas no coinciden";
                        }
        
                       
                        
                    } else {
                        echo "usuario con espacios o cadena de texto mayor a 12 caracteres";
                    }
                }          
                                    
                                }
            
        }

        function model_user_invite_post($dta) {
    
            require_once 'database/db_users.php'; // Asegúrate de proporcionar la ruta correcta al archivo de conexión a la base de datos
    
            // Realiza la conexión a la base de datos (reemplaza conn() con tu propia lógica de conexión)
            $conectar = conn();
    
            // Verifica si la conexión se realizó correctamente
            if (!$conectar) {
                return "Error de conexión a la base de datos";
            }
    
            // Escapa los valores para prevenir inyección SQL
            $user = mysqli_real_escape_string($conectar, $dta['user']);
            $name = mysqli_real_escape_string($conectar, $dta['name']);
            $last_name = mysqli_real_escape_string($conectar, $dta['last_name']);
            $contact = mysqli_real_escape_string($conectar, $dta['contact']);
            $dato_encriptado = mysqli_real_escape_string($conectar, $dta['dato_encriptado']);
            $escaped_value = mysqli_real_escape_string($conectar, $dta['dato_encriptado']);
            $rol = mysqli_real_escape_string($conectar, $dta['rol']);
            $primeros_ocho = mysqli_real_escape_string($conectar, $dta['primeros_ocho']);
            $primeros_ocho2 = mysqli_real_escape_string($conectar, $dta['primeros_ocho2']);
            $primeros_ocho3 = mysqli_real_escape_string($conectar, $dta['primeros_ocho3']);
            $profile_id = mysqli_real_escape_string($conectar, $dta['profile_id']);
            $resource_id = mysqli_real_escape_string($conectar, $dta['resource_id']);
    


            $query= mysqli_query($conectar,"SELECT username FROM users where username='$user'");
            $nr=mysqli_num_rows($query);
        
            if($nr>=1){
                $info=[
        
                    'data' => "ups! el usuario ya tiene asignación."
                    
                ];
             echo json_encode(['info'=>$info]);
             //echo "ups! el id del repo está repetido , intenta nuevamente, gracias.";
            }else{
        
              
                $query= mysqli_query($conectar,"SELECT sub_days FROM profiles where profile_id='$profile_id'");
                

                $days=[];
            
                while($row = $query->fetch_assoc())
                {
                        $day=[
                            'days' => $row['sub_days']
                        ];
                        
                        array_push($days,$day);
                        
                }
                $row=$query->fetch_assoc();

                $response= json_encode(['days'=>$days]);

                $fechaActual = date("Y-m-d");
                $data = json_decode($response);

                foreach ($data->days as $character) {
                
                    $daysresult =  $character->days;
                
            
               
                    if (strpos($user, " ") === false && strlen($user) < 13 && preg_match('/^[^@#%&,:ñÑ]+$/', $user)) {
                                  
        
                        if (preg_match('/^(?=.*\d)(?=.*[A-Z])(?=.*[a-z])(?=.*[^\w\d\s:])([^\s]){8,}$/', $dato_encriptado) && strlen($user) > 4 && $dato_encriptado==$dato_encriptado)  {
                            $userm=$user."@lugma.tech";
                                        
        
                                if($rol=="student")
                                {
                                    $query= mysqli_query($conectar,"SELECT student_id FROM students where student_id='$resource_id'");
                                    $nr=mysqli_num_rows($query);
                                
                                    if($nr<1){

                                        $query= mysqli_query($conectar,"INSERT INTO users (username,name,last_name,contact,keyword,user_id,mail) VALUES ('$user','$name','$last_name','$contact','$dato_encriptado','$primeros_ocho','$userm')");
                                    
                                $query1= mysqli_query($conectar,"INSERT INTO profiles (profile_id,user_id,rol,imageUrl,sub_id,sub_days,sub_date) VALUES ('$primeros_ocho2','$primeros_ocho','$rol','$name','post_invite','$daysresult','$fechaActual')");
                    
                                    $query1= mysqli_query($conectar,"INSERT INTO students (student_id,profile_id,name,last_name,invite,type) VALUES ('$resource_id','$primeros_ocho2','$name','$last_name',0,'post_invite')");
                                    
                                    require_once 'env/domain.php';
                                    $sub_domaincon=new model_dom;
                                    $sub_domain=$sub_domaincon->dom();
                                    $url = $sub_domain.'/lugmaTools/apiTools/v1/postSchedule/';
                
                              // Definir los datos a enviar en la solicitud POST
                              $data = array(
                                  'user_id' => $primeros_ocho
                                  );
                              $curl = curl_init();
                              
                              // Configurar las opciones de la sesión cURL
                              curl_setopt($curl, CURLOPT_URL, $url);
                              curl_setopt($curl, CURLOPT_POST, true);
                              curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                              curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                             // curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
                              
                              // Ejecutar la solicitud y obtener la respuesta
                              $response1 = curl_exec($curl);
                              //var_dump($data);
                              // Cerrar la sesión cURL
                              curl_close($curl);
        
        
                                    //echo $username;
                                    //echo $primeros_ocho;
                                    return $primeros_ocho2." ".$primeros_ocho3." true"; // muestra "/mi-pagina.php?id=123"
                    //echo $response;
                    
                                
                                }else{
                                    echo "false false false";
                                    }
        
                                }
                                if($rol=="teacher")
                                {
                                    $query= mysqli_query($conectar,"SELECT teacher_id FROM teachers where teacher_id='$resource_id'");
                                    $nr=mysqli_num_rows($query);
                                
                                    if($nr<1){

                                        $query= mysqli_query($conectar,"INSERT INTO users (username,name,last_name,contact,keyword,user_id,mail) VALUES ('$user','$name','$last_name','$contact','$dato_encriptado','$primeros_ocho','$userm')");
                                    
                                $query1= mysqli_query($conectar,"INSERT INTO profiles (profile_id,user_id,rol,imageUrl,sub_id,sub_days,sub_date) VALUES ('$primeros_ocho2','$primeros_ocho','$rol','$name','post_invite','$daysresult','$fechaActual')");
                    
                                    $query1= mysqli_query($conectar,"INSERT INTO teachers (teacher_id,profile_id,name,last_name,invite,type) VALUES ('$resource_id','$primeros_ocho2','$name','$last_name',0,'post_invite')");
                                    
                                    require_once 'env/domain.php';
                                    $sub_domaincon=new model_dom;
                                    $sub_domain=$sub_domaincon->dom();
                                    $url = $sub_domain.'/lugmaTools/apiTools/v1/postSchedule/';
                
                              // Definir los datos a enviar en la solicitud POST
                              $data = array(
                                  'user_id' => $primeros_ocho
                                  );
                              $curl = curl_init();
                              
                              // Configurar las opciones de la sesión cURL
                              curl_setopt($curl, CURLOPT_URL, $url);
                              curl_setopt($curl, CURLOPT_POST, true);
                              curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                              curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                             // curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
                              
                              // Ejecutar la solicitud y obtener la respuesta
                              $response1 = curl_exec($curl);
                              //var_dump($data);
                              // Cerrar la sesión cURL
                              curl_close($curl);
        
                                    //echo $username;
                                    //echo $primeros_ocho;
                                    return $primeros_ocho2." ".$primeros_ocho3." true"; // muestra "/mi-pagina.php?id=123"
                    //echo $response;
                    
                                
                                }else{
                                        echo "false false false";
                                    }
        
                                }


                                if($rol=="coordinate")
                                {
                                    $query= mysqli_query($conectar,"SELECT coor_id FROM coordinates where coor_id='$resource_id'");
                                    $nr=mysqli_num_rows($query);
                                
                                    if($nr<1){

                                        $query= mysqli_query($conectar,"INSERT INTO users (username,name,last_name,contact,keyword,user_id,mail) VALUES ('$user','$name','$last_name','$contact','$dato_encriptado','$primeros_ocho','$userm')");
                                    
                                $query1= mysqli_query($conectar,"INSERT INTO profiles (profile_id,user_id,rol,imageUrl,sub_id,sub_days,sub_date) VALUES ('$primeros_ocho2','$primeros_ocho','$rol','$name','post_invite','$daysresult','$fechaActual')");
                    
                                    $query1= mysqli_query($conectar,"INSERT INTO coordinates (coor_id,profile_id,name,last_name,invite,type) VALUES ('$resource_id','$primeros_ocho2','$name','$last_name',0,'post_invite')");
                                    
                                    require_once 'env/domain.php';
                                    $sub_domaincon=new model_dom;
                                    $sub_domain=$sub_domaincon->dom();
                                    $url = $sub_domain.'/lugmaTools/apiTools/v1/postSchedule/';
                
                              // Definir los datos a enviar en la solicitud POST
                              $data = array(
                                  'user_id' => $primeros_ocho
                                  );
                              $curl = curl_init();
                              
                              // Configurar las opciones de la sesión cURL
                              curl_setopt($curl, CURLOPT_URL, $url);
                              curl_setopt($curl, CURLOPT_POST, true);
                              curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                              curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                             // curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
                              
                              // Ejecutar la solicitud y obtener la respuesta
                              $response1 = curl_exec($curl);
                              //var_dump($data);
                              // Cerrar la sesión cURL
                              curl_close($curl);
        
                                    //echo $username;
                                    //echo $primeros_ocho;
                                    echo $primeros_ocho2." ".$primeros_ocho3." true"; // muestra "/mi-pagina.php?id=123"
                    //echo $response;
                    
                                
                                }else{
                                        echo "false false false";
                                    }
        
                                }
                                if($rol=="api_user")
                                {
                                    $query= mysqli_query($conectar,"SELECT user_id FROM api_users where user_id='$resource_id'");
                                    $nr=mysqli_num_rows($query);
                                
                                    if($nr<1){

                                        $query= mysqli_query($conectar,"INSERT INTO users (username,name,last_name,contact,keyword,user_id,mail) VALUES ('$user','$name','$last_name','$contact','$dato_encriptado','$primeros_ocho','$userm')");
                                    
                                $query1= mysqli_query($conectar,"INSERT INTO profiles (profile_id,user_id,rol,imageUrl,sub_id,sub_days,sub_date) VALUES ('$primeros_ocho2','$primeros_ocho','$rol','$name','post_invite','$daysresult','$fechaActual')");
                    
                                    $query1= mysqli_query($conectar,"INSERT INTO api_users (user_id,profile_id,name,last_name,invite,type) VALUES ('$resource_id','$primeros_ocho2','$name','$last_name',0,'post_invite')");
                                    
                                    require_once 'env/domain.php';
                                    $sub_domaincon=new model_dom;
                                    $sub_domain=$sub_domaincon->dom();
                                    $url = $sub_domain.'/lugmaTools/apiTools/v1/postSchedule/';
                
                              // Definir los datos a enviar en la solicitud POST
                              $data = array(
                                  'user_id' => $primeros_ocho
                                  );
                              $curl = curl_init();
                              
                              // Configurar las opciones de la sesión cURL
                              curl_setopt($curl, CURLOPT_URL, $url);
                              curl_setopt($curl, CURLOPT_POST, true);
                              curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                              curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                             // curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
                              
                              // Ejecutar la solicitud y obtener la respuesta
                              $response1 = curl_exec($curl);
                              //var_dump($data);
                              // Cerrar la sesión cURL
                              curl_close($curl);
        
                                    //echo $username;
                                    //echo $primeros_ocho;
                                    return $primeros_ocho2." ".$primeros_ocho3." true";// muestra "/mi-pagina.php?id=123"
                    //echo $response;
                    
                                
                                }else{
                                    echo "false false false";
                                    }
        
                                }
                            
                           
                            //echo "La cadena contiene números, letras mayúsculas, minúsculas y símbolos";
                        } else {
                            echo "La contraseña debe contener minimo 8 caracteres (mayusculas*,minusculas*,numeros*,simbolos*) o las contraseñas no coinciden";
                        }
        
                       
                        
                    } else {
                        echo "usuario con espacios o cadena de texto mayor a 12 caracteres";
                    }
                }          
                                    
                                }
            
        }
    }
    
    
?>