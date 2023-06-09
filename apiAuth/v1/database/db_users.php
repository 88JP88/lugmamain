<?php



function conn() {
    $hostname = "31.170.167.153"; // o dirección IP del servidor de la base de datos remota
    $usuariodb = "u767251687_devlugmacore"; // reemplazar con el usuario de la base de datos
    $passworddb = "2*HtdrwM;yO"; // reemplazar con la contraseña de la base de datos
    $dbname = "u767251687_devlugmacore"; // reemplazar con el nombre de la base de datos

/*
    $hostname = "31.170.167.153"; // o dirección IP del servidor de la base de datos remota
    $usuariodb = "u767251687_devlugmacore"; // reemplazar con el usuario de la base de datos
    $passworddb = "2*HtdrwM;yO"; // reemplazar con la contraseña de la base de datos
    $dbname = "u767251687_devlugmacore";
    */
    try {
        // Conexión a la base de datos
        $conectar = mysqli_connect($hostname, $usuariodb, $passworddb, $dbname);

        // Establecer el conjunto de caracteres de la conexión
        if (!mysqli_set_charset($conectar, "utf8mb4")) {
            throw new Exception("Error al establecer el conjunto de caracteres: " . mysqli_error($conectar));
        }

        return $conectar;
    } catch (Exception $e) {
        // Manejo del error
        echo "Error al conectar con la base de datos: " . $e->getMessage();
        exit();
    }
}


?>