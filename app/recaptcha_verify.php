<?php
// recaptcha_verify.php

// Verify the reCAPTCHA response
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
$recaptchaResult = file_get_contents($recaptchaUrl, false, $context);
$recaptchaResult = json_decode($recaptchaResult, true);
?>
