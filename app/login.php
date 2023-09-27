<?php
session_start();

if ($_POST) {
    include("./bd.php");

    $recaptchaSecretKey = '6Le6yFkoAAAAALdxb13RdkQdxm4tO5b-SSmsUlI3'; // Replace with your reCAPTCHA secret key
    $recaptchaResponse = $_POST['g-recaptcha-response'];

    $recaptchaUrl = 'https://www.google.com/recaptcha/api/siteverify';
    $recaptchaData = [
        'secret' => $recaptchaSecretKey,
        'response' => $recaptchaResponse,
    ];

    $options = [
        'http' => [
            'header' => "Content-Type: application/x-www-form-urlencoded\r\n",
            'method' => 'POST',
            'content' => http_build_query($recaptchaData),
        ],
    ];

    $context = stream_context_create($options);
    $recaptchaResult = @file_get_contents($recaptchaUrl, false, $context);

    if ($recaptchaResult === false) {
        // Handle cURL error...
        $curlError = error_get_last();
        $errorMessage = isset($curlError['message']) ? $curlError['message'] : 'Unknown cURL error';
        $mensaje = 'cURL error: ' . $errorMessage;
    } else {
        $recaptchaResult = json_decode($recaptchaResult, true);
        
        if ($recaptchaResult !== null) {
            if (isset($recaptchaResult['success']) && $recaptchaResult['success']) {
                // reCAPTCHA verification passed, proceed with database validation
                $sentencia = $conexion->prepare("SELECT *, count(*) as n_usuario
                    FROM `tbl_usuarios`
                    WHERE usuario = :usuario
                    AND password = :password");

                $usuario = $_POST["usuario"];
                $contrasenia = $_POST["contrasenia"];

                $sentencia->bindParam(":usuario", $usuario);
                $sentencia->bindParam(":password", $contrasenia);
                $sentencia->execute();

                $registro = $sentencia->fetch(PDO::FETCH_LAZY);

                if ($registro["n_usuario"] == 1) {
                    // User with the provided username and password exists
                    $_SESSION['usuario'] = $registro["usuario"];
                    $_SESSION['logueado'] = TRUE;
                    header("Location: ./index.php");
                } else {
                    $mensaje = "Datos incorrectos";
                }
            } else {
                $mensaje = "Por favor, complete el CAPTCHA correctamente.";
            }
        } else {
            // Handle JSON decoding error
            $mensaje = 'Error decoding JSON response';
        }
    }
}
?>



<!-- The rest of your HTML and form -->


<!doctype html>
<html lang="en">

<head>
  <title>Login</title>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <!-- Bootstrap CSS v5.2.1 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">

  <!-- reCAPTCHA Script -->
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
<script type="text/javascript">
  var onloadCallback = function() {
    grecaptcha.render('html_element', {
      'sitekey' : '6Le6yFkoAAAAAN0Bjrn69NcmBo_-ry2wTDmFAlsF'
    });
  };
</script>
<script src="https://www.google.com/recaptcha/enterprise.js?render=KEY_ID"></script>
</head>


<body>
  <main class="container">
    <div class="row">
      <div class="col-md-4">
      </div>
      <div class="col-md-4">
        </br></br>
        <div class="card">
          <div class="card-header">
            Login
          </div>
          <div class="card-body">
            <?php if(isset($mensaje)){ ?>
            <div class="alert alert-danger" role="alert">
              <strong><?php echo $mensaje ?></strong> 
            </div>
            <?php } ?>
            <form action="" method="post" id="login-form" onsubmit="return onSubmit()">
    <div class="mb-3">
        <label for="usuario" class="form-label">Usuario:</label>
        <input type="text" class="form-control" name="usuario" id="usuario" placeholder="Escriba su usuario"> 
    </div>
    <div class="mb-3">
        <label for="contrasenia" class="form-label">Contraseña:</label>
        <input type="password" class="form-control" name="contrasenia" id="contrasenia" aria-describedby="helpId" placeholder="Escriba su contraseña">
    </div>
        <!-- Add the reCAPTCHA widget here -->
        <div class="g-recaptcha" data-sitekey="6Le6yFkoAAAAAN0Bjrn69NcmBo_-ry2wTDmFAlsF"></div>
        <br>
        <input type="submit" value="Submit">
</form>

          </div>
        </div>
      </div>
    </div>
  </main>
  <footer>
    <!-- place footer here -->
  </footer>

  <!-- Bootstrap JavaScript Libraries -->
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"
    integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous"></script>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.min.js"
    integrity="sha384-7VPbUDkoPSGFnVtYi0QogXtr74QeVeeIs99Qfg5YCF+TidwNdjvaKZX19NZ/e6oz" crossorigin="anonymous"></script>

  <script>
    var onSubmit = function () {
      var response = grecaptcha.getResponse();
      if (response.length === 0) {
        alert("Please complete the CAPTCHA.");
        return false; // Prevent form submission
      }
      return true; // Allow form submission
    };
  </script>
</body>

</html>
