<?php
// contact-process.php - Updated with Email Notifications
require_once 'db_config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 1. Sanitize Data
    $name = mysqli_real_escape_string($conn, strip_tags($_POST['name']));
    $email = mysqli_real_escape_string($conn, filter_var($_POST['email'], FILTER_SANITIZE_EMAIL));
    $service = mysqli_real_escape_string($conn, strip_tags($_POST['service']));
    $msg = mysqli_real_escape_string($conn, strip_tags($_POST['message']));

    // 2. Validation
    if (empty($name) || empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header("Location: index.php?status=error");
        exit;
    }

    // 3. Save to Database
    $sql = "INSERT INTO {$TABLE_PREFIX}leads (name, email, service, message) VALUES ('$name', '$email', '$service', '$msg')";
    
    if (mysqli_query($conn, $sql)) {
        
        // 4. PREPARE EMAIL NOTIFICATION
        $to = $SETTINGS['company_email']; // Automatically pulls from your Settings page
        $subject = "New Business Lead: $service from $name";
        
        // Professional Email Body (HTML formatted)
        $email_content = "
        <html>
        <head><title>New Lead Notification</title></head>
        <body style='font-family: Arial, sans-serif; line-height: 1.6;'>
            <h2 style='color: #6c63ff;'>You have a new inquiry!</h2>
            <p><strong>Name:</strong> $name</p>
            <p><strong>Email:</strong> $email</p>
            <p><strong>Service Requested:</strong> $service</p>
            <p><strong>Message:</strong><br> $msg</p>
            <hr>
            <p style='font-size: 12px; color: #888;'>This message was sent automatically from your JAWeb Portfolio Dashboard.</p>
        </body>
        </html>
        ";

        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        $headers .= "From: <" . $SETTINGS['company_email'] . ">" . "\r\n";

        // 5. Send Email
        @mail($to, $subject, $email_content, $headers);

        header("Location: index.php?status=success#contact");
    } else {
        header("Location: index.php?status=db_error");
    }
}