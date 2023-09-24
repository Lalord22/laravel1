<?php
include("../../bd.php");

if(isset( $_GET['txtID'] )){
     $txtID=(isset($_GET[ 'txtID' ]))?$_GET['txtID']:"";

    $sentencia=$conexion->prepare("SELECT *,(SELECT nombredelpuesto 
    FROM tbl_puestos 
    WHERE tbl_puestos.id=tbl_empleados.idpuesto limit 1) as puesto FROM tbl_empleados WHERE id=:id" );
    $sentencia->bindParam(":id",$txtID);
    $sentencia->execute();
    $registro=$sentencia->fetch(PDO::FETCH_LAZY);

    $primernombre=$registro["primernombre"];
    $segundonombre=$registro["segundonombre"];
    $primerapellido=$registro["primerapellido"];
    $segundoapellido=$registro["segundoapellido"];

    $nombreCompleto=$primernombre." ".$segundonombre." ".$primerapellido." ".$segundoapellido; 

    $foto=$registro["foto"];
    $cv=$registro["cv"];
    $idpuesto=$registro["idpuesto"];
    $puesto=$registro["puesto"];
    $fechadeingreso=$registro["fechadeingreso"];

    $fechaInicio= new DateTime($fechadeingreso);
    $fechaActual= new DateTime(date("Y-m-d"));
    $diferencia=date_diff($fechaInicio,$fechaActual);
    /*
    $sentencia=$conexion->prepare("SELECT * FROM tbl_empleados WHERE id=:id" );
    $sentencia->bindParam(":id",$txtID);
    $sentencia->execute();
    */
    $registro=$sentencia->fetch(PDO::FETCH_LAZY);
   
    

   

}

ob_start();  //Empieza a grabar los datos en memoria
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible"
              content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Carta de recomendacion</title>
        <link rel="stylesheet" href="../../css/bootstrap.min.css">
    </head>
    <body>
        <h1>Carta de recomendacion Laboral</h1>
        <br/><br/>
        San Jose, Costa Rica <strong> <?php echo date('d M Y'); ?>  </strong>
        <br/><br/>A quien pueda interesar:
        <br/><br/>Reciba un cordial saludo.
        <br/><br/>A traves de estas lineas deseo hacer de su conocimiento que la Sr(a) <strong> <?php echo $nombreCompleto; ?> </strong>, quien laboro en nuestra organizacion por 
        <strong> <?php echo $diferencia->y; ?> años </strong> es un ciudadano intachable. Ha demostrado ser un excelente trabajador, 
        comprometido, responsable, y fiel cumplidor de sus tareas.
        Siempre ha manifestado preocupacion por mejorar, capacitarse y actualizar sus conocimientos.
        <br/><br/>
        Durante estos años se ha desempeñado como: <strong> <?php echo $puesto; ?> </strong> 
        Es por ello que le sugiero considere esta recomendacion, con la confianza de que estara siempre a la altura de sus compromisos y responsabilidades.
        <br/><br/>Sin otro particular, me despido.

    </body>
</html> 
<?php 
$HTML=ob_get_clean(); //Toma lo que hay en memoria y lo guarda en la variable HTML  

require_once("../../libs/autoload.inc.php");
use Dompdf\Dompdf;
$dompdf=new Dompdf();
$opciones=$dompdf->getOptions();
$opciones->set(array("isRemoteEnabled",true));
$dompdf->setOptions($opciones); 
$dompdf->loadHtml($HTML);
$dompdf->setPaper('letter');
$dompdf->render();
$dompdf->stream("archivo.pdf",array("Attachment"=>false));
?>


