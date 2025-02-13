<?php
require 'config.php';

// Configuration des en-têtes pour éviter les problèmes de caractères spéciaux
header('Content-Type: text/html; charset=utf-8');

// Récupération des données du formulaire
$name = isset($_POST['name']) ? htmlspecialchars($_POST['name']) : '';
$email = isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '';
$message = isset($_POST['message']) ? htmlspecialchars($_POST['message']) : '';

// Préparation du message HTML
$email_content = "
<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #f8f9fa; padding: 20px; border-bottom: 2px solid #00f3ff; }
        .content { padding: 20px; }
        .footer { text-align: center; padding: 20px; font-size: 0.8em; color: #666; }
    </style>
</head>
<body>
    <div class='container'>
        <div class='header'>
            <h2>Nouveau message de contact - Larasoft</h2>
        </div>
        <div class='content'>
            <p><strong>Nom:</strong> {$name}</p>
            <p><strong>Email:</strong> {$email}</p>
            <p><strong>Message:</strong><br>{$message}</p>
        </div>
        <div class='footer'>
            <p>Ce message a été envoyé via le formulaire de contact Larasoft</p>
        </div>
    </div>
</body>
</html>
";

// Configuration des en-têtes de l'email
$headers = "MIME-Version: 1.0\r\n";
$headers .= "Content-type: text/html; charset=utf-8\r\n";
$headers .= "From: " . SMTP_FROM_NAME . " <" . SMTP_FROM_EMAIL . ">\r\n";
$headers .= "Reply-To: {$email}\r\n";
$headers .= "X-Mailer: PHP/" . phpversion();

// Configuration SMTP
ini_set("SMTP", SMTP_HOST);
ini_set("smtp_port", SMTP_PORT);
ini_set("sendmail_from", SMTP_FROM_EMAIL);

// Adresse email de destination
$to = SMTP_FROM_EMAIL;

// Sujet du mail
$subject = 'Nouveau message de contact - Larasoft';

// Envoi de l'email
$mail_sent = mail($to, $subject, $email_content, $headers);

// Préparation de la réponse
if($mail_sent) {
    $response = [
        'status' => 'success',
        'message' => 'Votre message a été envoyé avec succès! Nous vous répondrons dans les plus brefs délais.'
    ];
} else {
    $response = [
        'status' => 'error',
        'message' => 'Une erreur est survenue lors de l\'envoi du message. Veuillez réessayer plus tard.'
    ];
}

// Retour de la réponse en JSON
header('Content-Type: application/json');
echo json_encode($response);
?>
