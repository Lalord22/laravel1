<?php
echo "recaptcha_verify.php is being executed.";

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
$recaptchaResult = @file_get_contents($recaptchaUrl, false, $context);

if ($recaptchaResult === false) {
    // Handle cURL error...
    $curlError = error_get_last();
    $errorMessage = isset($curlError['message']) ? $curlError['message'] : 'Unknown cURL error';
    echo json_encode(['success' => false, 'error' => 'cURL error: ' . $errorMessage]);
} else {
    // Debugging: Log the response for inspection
    file_put_contents('recaptcha_response.log', $recaptchaResult);

    $recaptchaResult = json_decode($recaptchaResult, true);
    if ($recaptchaResult !== null) {
        if (isset($recaptchaResult['success']) && $recaptchaResult['success']) {
            // reCAPTCHA verification passed
            echo json_encode(['success' => true]);
        } else {
            // reCAPTCHA verification failed
            echo json_encode(['success' => false]);
        }
    } else {
        // Handle JSON decoding error
        echo json_encode(['success' => false, 'error' => 'Error decoding JSON response']);
    }
}
?>
