

<?php

// ... (forgotPassword function code as above) ...

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

// Example usage (replace with actual settings)
$email = "test@example.com";
if (forgotPassword($email)) {
    echo "Password reset email sent successfully.
";
} else {
    echo "Failed to send password reset email.
";
}

?>
