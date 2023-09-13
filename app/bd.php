<?php

$servidor="localhost";  //127.0.0.1(local), o la direccion del servidor externo
$baseDeDatos="app";
$usuario="root";
$contrasenia="";

try{
    $conexion= new PDO("mysql:host=$servidor;dbname=$baseDeDatos",$usuario,$contrasenia);
}catch(Exception $ex){
    echo $ex->getMessage();
}


?>