<?php


include("../../bd.php");

$months = [
    'January' => 'enero',
    'February' => 'febrero',
    'March' => 'marzo',
    'April' => 'abril',
    'May' => 'mayo',
    'June' => 'junio',
    'July' => 'julio',
    'August' => 'agosto',
    'September' => 'septiembre',
    'October' => 'octubre',
    'November' => 'noviembre',
    'December' => 'diciembre'
];

$date = date('d F Y'); // Use 'F' to get the full month name in English
$dateParts = explode(' ', $date);

$day = $dateParts[0];
$month = $months[$dateParts[1]];
$year = $dateParts[2];

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
        <br/><br/>
        <?php echo "{$day} {$month}";?> del <?php echo "{$year}";?> 
        <br/><br/>Departamento Financiero
        <br/>SINART, S.A.
        <br/><br/>Reciba un cordial saludo de nuestra parte, el presente documento es para solicitar la reserva presupuestaria de la pauta del mes <strong><?php echo "{$month} {$year}" ;?> </strong>para el cliente <strong> <?php echo $nombreCompleto; ?> </strong>
        <br/><br/>Pauta en: <strong> <?php echo $puesto; ?> </strong>
        <br/><br/>Campaña: <strong> <?php echo $puesto; ?> </strong>
        <br/><br/>Orden de compra Sinart: <strong> <?php echo $txtID; ?> </strong>
        <br/><br/>No se contempla el 10% de pauta del Sinart ni la pauta en digital.
        <br/><br/>Monto total de la pauta: <strong> <?php echo $puesto; // aca va el la variable del monto ?> </strong>
        <br><br>Se adjunta validaciones al final de la firma
        <br/><br/>Cualquier consulta con todo gusto.
        <br/><br/>Atentamente, Director General
        <br/><br/>Sub Director General                            Por MEDIOS
        

    </body>
</html> 
<?php 
error_reporting(E_ALL);
require '../../../vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;


use Dompdf\Dompdf;

try{
$HTML=ob_get_clean(); //Toma lo que hay en memoria y lo guarda en la variable HTML  



require_once("../../libs/autoload.inc.php");

$dompdf=new Dompdf();
$opciones=$dompdf->getOptions();
$opciones->set(array("isRemoteEnabled",true));
$dompdf->setOptions($opciones); 
$dompdf->loadHtml($HTML);
$dompdf->setPaper('letter');
$dompdf->render();
$dompdf->stream("archivo.pdf",array("Attachment"=>false));
}catch(Exception $e){
    echo "PDF could not be generated: {$e->getMessage()}";
}


/*
try {

    $mail = new PHPMailer(true);
    // SMTP configuration
    $mail->isSMTP();
    $mail->Host       = "mail.smtp2go.com";
    $mail->SMTPAuth = true; // Enable SMTP authentication
    $mail->Username   = 'est.una.ac.cr';
    $mail->Password   = 'Nlr6psVQ907VlcKp';
    $mail->SMTPSecure = 'tls';
    $mail->Port       = 2525;

    

    // Recipients and email content
    $mail->setFrom('lalo22296@hotmail.com', 'Gerardo Salazar - Testing Sinart');
    $mail->addAddress('testingsinart@gmail.com', 'Cliente'); // Persona que recibe el correo
    $mail->addStringAttachment($dompdf->output(), 'archivo.pdf', 'base64', 'application/pdf');
    $mail->isHTML(true);
    $mail->Subject = 'Carta de Recomendacion';
    $mail->Body    = $HTML;

    // Send the email
    $mail->send();
    echo 'Email has been sent successfully';
} catch (Exception $e) {
    echo "Email could not be sent. Mailer Error: {$mail->ErrorInfo}";
}
*/
?>


