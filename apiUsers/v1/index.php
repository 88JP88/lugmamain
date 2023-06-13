<?php

require 'flight/Flight.php';

require_once 'database/db_users.php';



Flight::route('POST /post', function () {
   
$dta = [
        'user' => Flight::request()->data->user,
        'name' => Flight::request()->data->name,
        'last_name' => Flight::request()->data->last_name,
        'contact' => Flight::request()->data->contact,
        'pass' => Flight::request()->data->pass,
        'pass1' => Flight::request()->data->pass1,
        'rol' => Flight::request()->data->rol,
        'subscription' => Flight::request()->data->subscription,
        'code' => Flight::request()->data->code,
        'word' => Flight::request()->data->word
    ];

        require_once('../../apiUsers/v1/controller/users/post_functions.php');
        
            $post_users = new post_functions();
            echo $post_users->post_users($dta);
           // echo json_encode($dta);
     

});

Flight::route('POST /postSub', function () {
    $conectar=conn();

    $dta = [
        'mail' => Flight::request()->data->mail,
        'word' => Flight::request()->data->word,
        'type' => Flight::request()->data->type
    ];
$word=Flight::request()->data->word;
$mail=Flight::request()->data->mail;
$type=Flight::request()->data->type;
   

    require('../../apiUsers/v1/model/modelSecurity/crypt/cryptic.php');


//$alfanumerico = bin2hex(random_bytes(50));

$dato = "Esta es informacion importante";
//Encripta informaciÃ³n:
$dato_encriptado = $encriptar($word);
    require('../../apiUsers/v1/model/modelSecurity/uuid/uuidd.php');
    $con=new generateUuid();
        $myuuid = $con->guidv4();
        $primeros_ocho = substr($myuuid, 0, 8);
    $query= mysqli_query($conectar,"SELECT sub_id FROM subscriptionlist where mail='$mail' and status=1");
    $nr=mysqli_num_rows($query);

    if($nr>=1){
        $info=[

            'data' => "ups! el nombrede usuario está repetido , intenta nuevamente, gracias."
            
        ];
     echo json_encode(['info'=>$info]);
     //echo "ups! el id del repo está repetido , intenta nuevamente, gracias.";
    }else{

        //$correo = "ejemplo@dominio.com"; // Cambia esta cadena por la que quieras verificar

        if (filter_var($mail, FILTER_VALIDATE_EMAIL)) {

          if  (preg_match('/^(?=.*\d)(?=.*[A-Z])(?=.*[a-z])(?=.*[^\w\d\s:])([^\s]){8,}$/', $word) && strlen($word) > 7)  
                    {
            $query= mysqli_query($conectar,"INSERT INTO subscriptionlist (sub_id,mail,secret_word,sub_type) VALUES ('$primeros_ocho','$mail','$dato_encriptado','$type')");
            ini_set( 'display_errors', 1 );
            error_reporting( E_ALL );
            $from = "lugma@lugma.tech";
            $to = $mail;
            $subject = "Código de suscripción a LUGMA";
            $message = "TU CÓDIGO DE SUSCRIPCIÓN ES: ".$primeros_ocho.".<br>Ingresa este código cuando crees tu usuario, y recuerda tu palabra secreta...";
            $headers = "From:" . $from;
            mail($to,$subject,$message, $headers);
            echo "The email message was sent.";
    
    echo "Tu código de suscripción fue enviado al correo adjunto: ".$mail;
                    }else{
                        echo "Palabra clave muy corta o no contiene caracteres mayusculas minusculas o simbolos minumo 8 caracteres";
                    }
        } 
        else {
            echo "Correo incorrecto";
        }
        

   
    
   
    }
});




Flight::route('POST /putPass', function () {
    $conectar=conn();
    $uri = $_SERVER['REQUEST_URI'];


    $pass=(Flight::request()->data->pass);
    $npass=(Flight::request()->data->npass);
    $npass2=(Flight::request()->data->npass2);
    $user=(Flight::request()->data->user_id);

    
    $query= mysqli_query($conectar,"SELECT name FROM users where keyword='$pass' and user_id='$user'");
    $nr=mysqli_num_rows($query);

    if($nr<=0){
        $info=[

            'data' => "ups! la contraseña es incorrecta , intenta nuevamente, gracias."
            
        ];
     echo json_encode(['info'=>$info]);
     //echo "ups! el id del repo está repetido , intenta nuevamente, gracias.";
    }else{

      if($npass==$npass2){

        $query= mysqli_query($conectar,"UPDATE users SET keyword='$npass2' WHERE user_id='$user'");
       
    echo 'true';
       
    

      }else{

        $info=[

            'data' => "ups! las contraseñas no coinciden , intenta nuevamente, gracias."
            
        ];
     echo json_encode(['info'=>$info]);
      }

   
    }
});

Flight::route('POST /putUser', function () {
    $conectar=conn();

    $name=(Flight::request()->data->name);
    $last_name=(Flight::request()->data->last_name);
    $contact=(Flight::request()->data->contact);
    $user_id=(Flight::request()->data->user_id);
    $public=(Flight::request()->data->public);

   

   
    $query= mysqli_query($conectar,"UPDATE users SET name='$name',last_name='$last_name',contact='$contact',is_public='$public' WHERE user_id='$user_id'");
       
    
   
    echo "true"; // muestra "/mi-pagina.php?id=123"

    }
);

Flight::route('POST /validate', function () {
    header("Access-Control-Allow-Origin: *");
    $conectar=conn();
    $uri = $_SERVER['REQUEST_URI'];


    $user1=(Flight::request()->data->user);
    $pass=(Flight::request()->data->pass);

    require('../../apiUsers/v1/model/modelSecurity/uuid/uuidd.php');
    $con=new generateUuid();
        $myuuid = $con->guidv4();
        //$primeros_ocho = substr($myuuid, 0, 8);
        require('../../apiUsers/v1/model/modelSecurity/crypt/cryptic.php');


//$alfanumerico = bin2hex(random_bytes(50));

$dato = "Esta es informacion importante";
//Encripta informaciÃ³n:
$dato_encriptado = $encriptar($pass);
    $query= mysqli_query($conectar,"SELECT username FROM users where keyword='$dato_encriptado' and username='$user1' and session_counter <=2");
    $nr=mysqli_num_rows($query);

    if($nr>=1){
        
        $query= mysqli_query($conectar,"SELECT session_counter,user_id FROM users where username='$user1'");
       

        $users=[];
 
        while($row = $query->fetch_assoc())
        {
                $user=[
                    'counter' => $row['session_counter'],
                    'user' => $row['user_id']
                ];
                
                array_push($users,$user);
                
        }
        $row=$query->fetch_assoc();

        $response= json_encode(['users'=>$users]);
       
        $data = json_decode($response);
        foreach ($data->users as $character) {
            
        }
    
        $suma=  $character->counter;
        $us= $character->user;
        $suma1=$suma+1;


        if($suma1<=3){

        $query= mysqli_query($conectar,"UPDATE users SET session_counter='$suma1' WHERE username='$user1'");
         
  
        $query= mysqli_query($conectar,"SELECT sub_date,sub_days FROM profiles where user_id='$us'");
         
  
        $subs=[];
    
        while($row = $query->fetch_assoc())
        {
                $sub=[
                    'date' => $row['sub_date'],
                    'days' => $row['sub_days']
                ];
                
                array_push($subs,$sub);
                
        }
        $row=$query->fetch_assoc();
    
        $response= json_encode(['subs'=>$subs]);
       
  
        $data = json_decode($response);
        foreach ($data->subs as $character) {
          $fechaActual = date("Y-m-d");
            $resultado =  $character->date;
            $resultado1 =  $character->days;
            if($resultado==$fechaActual){
              echo "true";
            }if($resultado!=$fechaActual){
              $resta=$resultado1-1;
              $query1= mysqli_query($conectar,"UPDATE profiles SET sub_days='$resta',sub_date='$fechaActual' where user_id='$us'");
              echo "true";
            }
    
             
        }
             
        
    
   
      }else{

        echo "false";
        //echo "ups! el id del repo está repetido , intenta nuevamente, gracias.";
     

   
   
    }
}else{
    echo "false";
}
});



Flight::route('POST /validateOut', function () {
    $conectar=conn();
    //$uri = $_SERVER['REQUEST_URI'];


    $user1=(Flight::request()->data->user);

    
        //$primeros_ocho = substr($myuuid, 0, 8);
       
    
        
        $query= mysqli_query($conectar,"SELECT session_counter,user_id FROM users where username='$user1'");
       

        $users=[];
 
        while($row = $query->fetch_assoc())
        {
                $user=[
                    'counter' => $row['session_counter'],
                    'user' => $row['user_id']
                ];
                
                array_push($users,$user);
                
        }
        $row=$query->fetch_assoc();

        $response= json_encode(['users'=>$users]);
       
        $data = json_decode($response);
        foreach ($data->users as $character) {
          
           
        }
    
      $suma=  $character->counter;
      $us=  $character->user;
      $suma1=$suma-1;
      $query= mysqli_query($conectar,"UPDATE users SET session_counter='$suma1' WHERE username='$user1'");
       

  
           
      echo "true";

    
   
      
});


Flight::route('GET /get/@id', function ($id) {
    header("Access-Control-Allow-Origin: *");
    $conectar=conn();
    $uri = $_SERVER['REQUEST_URI'];


    $query= mysqli_query($conectar,"SELECT u.user_id,u.username,u.name,u.last_name,u.contact,p.rol,p.profile_id,p.imageUrl,p.sub_days,u.is_public,u.mail FROM users u JOIN profiles p ON p.user_id=u.user_id  where u.username='$id'");
       

        $users=[];
 
        while($row = $query->fetch_assoc())
        {
                $user=[
                    'user_id' => $row['user_id'],
                    'username' => $row['username'],
                    'name' => $row['name'],
                    'last_name' => $row['last_name'],
                    'contact' => $row['contact'],
                    'rol' => $row['rol'],
                    'profile' => $row['profile_id'],
                    'image' => $row['imageUrl'],
                    'days' => $row['sub_days'],
                    'public' => $row['is_public'],
                    'internal_mail' => $row['mail']
                ];
                
                array_push($users,$user);
                
        }
        $row=$query->fetch_assoc();

        echo json_encode(['users'=>$users]);
       
  
  // echo $uri; // muestra "/mi-pagina.php?id=123"

       
   

});

Flight::route('GET /getMyProfileByProfile/@id', function ($id) {
    header("Access-Control-Allow-Origin: *");
    $conectar=conn();
    $uri = $_SERVER['REQUEST_URI'];


    $query= mysqli_query($conectar,"SELECT u.user_id,u.username,u.name,u.last_name,u.contact,p.rol,p.profile_id,p.imageUrl,p.sub_days,u.is_public,u.mail FROM users u JOIN profiles p ON p.user_id=u.user_id  where p.profile_id='$id'");
       

        $users=[];
 
        while($row = $query->fetch_assoc())
        {
                $user=[
                    'user_id' => $row['user_id'],
                    'username' => $row['username'],
                    'name' => $row['name'],
                    'last_name' => $row['last_name'],
                    'contact' => $row['contact'],
                    'rol' => $row['rol'],
                    'profile' => $row['profile_id'],
                    'image' => $row['imageUrl'],
                    'days' => $row['sub_days'],
                    'public' => $row['is_public'],
                    'internal_mail' => $row['mail']
                ];
                
                array_push($users,$user);
                
        }
        $row=$query->fetch_assoc();

        echo json_encode(['users'=>$users]);
       
  
  // echo $uri; // muestra "/mi-pagina.php?id=123"

       
   

});


Flight::route('GET /getByProfile/@id', function ($id) {
    header("Access-Control-Allow-Origin: *");
    $conectar=conn();
    $uri = $_SERVER['REQUEST_URI'];


    $query= mysqli_query($conectar,"SELECT u.user_id,u.username,u.name,u.last_name,u.contact,p.rol,p.profile_id,p.imageUrl,p.sub_days,u.is_public,u.mail FROM users u JOIN profiles p ON p.user_id=u.user_id  where p.profile_id='$id'");
       

        $users=[];
 
        while($row = $query->fetch_assoc())
        {
                $user=[
                    'user_id' => $row['user_id'],
                    'username' => $row['username'],
                    'name' => $row['name'],
                    'last_name' => $row['last_name'],
                    'contact' => $row['contact'],
                    'rol' => $row['rol'],
                    'profile' => $row['profile_id'],
                    'image' => $row['imageUrl'],
                    'days' => $row['sub_days'],
                    'public' => $row['is_public'],
                    'internal_mail' => $row['mail']
                ];
                
                array_push($users,$user);
                
        }
        $row=$query->fetch_assoc();

        echo json_encode(['users'=>$users]);
       
  
  // echo $uri; // muestra "/mi-pagina.php?id=123"

       
   

});



Flight::route('GET /getAll/', function () {
    header("Access-Control-Allow-Origin: *");
    $conectar=conn();
    $uri = $_SERVER['REQUEST_URI'];


    $query= mysqli_query($conectar,"SELECT u.user_id,u.username,u.name,u.last_name,u.contact,p.rol,p.profile_id,p.imageUrl,p.sub_days,u.is_public,u.mail FROM users u JOIN profiles p ON p.user_id=u.user_id");
       

        $users=[];
 
        while($row = $query->fetch_assoc())
        {
                $user=[
                   
                    'username' => $row['username'],
                    'name' => $row['name'],
                    'last_name' => $row['last_name'],
                    'contact' => $row['contact'],
                    'profile_id' => $row['profile_id'],
                    'image' => $row['imageUrl'],
                    'internal_mail' => $row['mail']
                ];
                
                array_push($users,$user);
                
        }
        $row=$query->fetch_assoc();

        echo json_encode(['users'=>$users]);
       
  
  // echo $uri; // muestra "/mi-pagina.php?id=123"

       
   

});

Flight::route('GET /getPublicUsers/', function () {
    header("Access-Control-Allow-Origin: *");
    $conectar=conn();
    $uri = $_SERVER['REQUEST_URI'];


    $query= mysqli_query($conectar,"SELECT u.user_id,u.username,u.name,u.last_name,u.contact,p.rol,p.profile_id,p.imageUrl,p.sub_days FROM users u JOIN profiles p ON p.user_id=u.user_id  where u.is_public=1");
       

        $users=[];
 
        while($row = $query->fetch_assoc())
        {
                $user=[
                    'username' => $row['username'],
                    'name' => $row['name'],
                    'last_name' => $row['last_name'],
                    'contact' => $row['contact'],
                    'rol' => $row['rol'],
                    'profile' => $row['profile_id'],
                    'image' => $row['imageUrl'],
                    'user_id' => $row['user_id']
                ];
                
                array_push($users,$user);
                
        }
        $row=$query->fetch_assoc();

        echo json_encode(['users'=>$users]);
       
  
  // echo $uri; // muestra "/mi-pagina.php?id=123"

       
   

});




Flight::route('GET /getSubs/', function () {
    header("Access-Control-Allow-Origin: *");
    $conectar=conn();
    //$uri = $_SERVER['REQUEST_URI'];


    $query= mysqli_query($conectar,"SELECT sub_id,name,info,total_ammount,day_amount,rate_day,sub_type FROM subscriptions where status=1 and is_active=1");
       

        $subs=[];
 
        while($row = $query->fetch_assoc())
        {
                $sub=[
                    'sub_id' => $row['sub_id'],
                    'name' => $row['name'],
                    'info' => $row['info'],
                    'total' => $row['total_ammount'],
                    'day' => $row['day_amount'],
                    'rate' => $row['rate_day'],
                    'type' => $row['sub_type']
                ];
                
                array_push($subs,$sub);
                
        }
        $row=$query->fetch_assoc();

        echo json_encode(['subs'=>$subs]);
       
  
  // echo $uri; // muestra "/mi-pagina.php?id=123"

       
   

});


Flight::start();
