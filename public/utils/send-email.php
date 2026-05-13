<?php

require '../../vendor/autoload.php'; // If installed via Composer

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// start cors
if (isset($_SERVER['HTTP_ORIGIN'])) {
    header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
    header('Access-Control-Allow-Credentials: true');
    header('Access-Control-Max-Age: 86400');    // cache for 1 day
}

// Access-Control headers are received during OPTIONS requests
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
        header("Access-Control-Allow-Methods: GET, POST, PUT, OPTIONS");         

    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
        header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");

    exit(0);
}

// required headers
header("Access-Control-Allow-Origin: *");
header('Access-Control-Allow-Credentials: true');
header("Access-Control-Allow-Methods: HEAD, GET, POST, PUT, PATCH, DELETE, OPTIONS");
header('Access-Control-Allow-Headers: Authorization, Content-Type, x-xsrf-token, x_csrftoken, Cache-Control, X-Requested-With');

$mail = new PHPMailer(true);

try {
    // Get JSON input
    $json = file_get_contents('php://input');
    $data = json_decode($json, true);

    // === Gmail SMTP configuration ===
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'arteemplanejadosbr@gmail.com';       // your Gmail address
    $mail->Password   = 'rqrq gify lzxk nqkd'; // 'xvpu qhwg nzre xcxl';         // your Gmail App Password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 587;

    // === Sender & recipient ===
    $mail->setFrom('arteemplanejadosbr@gmail.com', 'Arte em Planejados');
    $mail->addAddress($data['email'], $data['name']);
    $mail->addAddress('arteemplanejadosbr@gmail.com', 'Arte em Planejados');

    // === Email content ===
    $mail->isHTML(true);
    $mail->Subject = $data['subject'];
    $mail->Body    = '
        <html>
        <body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
            <h2 style="color: #007bff;">' . htmlspecialchars($data['subject']) . '</h2>
            <table cellspacing="0" cellpadding="6" border="0" style="width:100%; max-width:600px;">
                <tr>
                    <td style="width:150px;"><strong>Name:</strong></td>
                    <td>' . htmlspecialchars($data['name']) . '</td>
                </tr>
                <tr>
                    <td><strong>Email:</strong></td>
                    <td>' . htmlspecialchars($data['email']) . '</td>
                </tr>
                <tr>
                    <td><strong>Subject:</strong></td>
                    <td>' . htmlspecialchars($data['subject']) . '</td>
                </tr>
                <tr>
                    <td><strong>Message:</strong></td>
                    <td style="white-space: pre-wrap;">' . nl2br(htmlspecialchars($data['message'])) . '</td>
                </tr>
            </table>
            <hr>
            <p style="font-size: 12px; color: #777;">This message was sent from your website contact form.</p>
        </body>
        </html>
    ';
    //$mail->AltBody = 'Hello! This email was sent using Gmail SMTP + PHPMailer.';

    // === Send ===
    $mail->send();
    echo json_encode(array("message" => "email_sent_successfully"));
} catch (Exception $e) {
    echo $e;
}
?>
