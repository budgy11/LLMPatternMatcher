

<?php

// Assuming you have a database connection established (e.g., $db)

/**
 * Resets a user's password.
 *
 * @param string $email The user's email address.
 * @return bool True if a reset token was generated and sent, false otherwise.
 */
function forgot_password(string $email): bool
{
    // 1. Check if the email exists in the database
    $user = getUserByEmail($email);

    if (!$user) {
        // User not found
        return false;
    }

    // 2. Generate a unique reset token
    $token = generate_unique_token(); // Implement this function (see below)

    // 3. Store the token in the database, associated with the user's email.
    //    This is crucial for security.  Don't just store a plain token.
    //    Ideally, you'd hash the token and store the hash.  Storing the raw
    //    token directly is vulnerable to attacks.
    store_reset_token($user->id, $token);  //Implement this function (see below)

    // 4. Send the reset email.
    $subject = 'Password Reset';
    $body = "Please click the following link to reset your password: " .  base_url() . "/reset_password?token=" . $token; // Replace with your base URL
    $headers = "From: " . get_admin_email() . "\r
"; // Replace with your admin email
    $result = send_email($email, $subject, $body, $headers);


    if ($result) {
        return true;
    } else {
        // Email sending failed.  Consider logging this error for debugging.
        return false;
    }
}


/**
 *  Dummy functions - Implement these based on your setup.
 */

/**
 * Gets a user by email.  Implement this to connect to your database.
 * @param string $email
 * @return User | null
 */
function getUserByEmail(string $email): ?User {
    // *** IMPLEMENT THIS FUNCTION ***
    // This is a placeholder. Replace with your database query.
    // Example (using a hypothetical User class):
    // $result = mysqli_query($db, "SELECT * FROM users WHERE email = '$email'");
    // if (mysqli_num_rows($result) > 0) {
    //   $row = mysqli_fetch_assoc($result);
    //   return new User($row);
    // }
    // return null;
}



/**
 * Generates a unique token.  Use a cryptographically secure random number generator.
 * @return string
 */
function generate_unique_token(): string
{
    return bin2hex(random_bytes(32)); // A 32-byte (256-bit) random number.
}



/**
 * Stores the reset token in the database.  HASH the token for security.
 * @param int $userId
 * @param string $token
 * @return void
 */
function store_reset_token(int $userId, string $token): void
{
    // *** IMPLEMENT THIS FUNCTION ***
    // Example (using a hypothetical database table called 'reset_tokens'):
    // mysqli_query($db, "INSERT INTO reset_tokens (user_id, token, created_at) VALUES ('$userId', '$token', NOW())");
    // OR using an ORM:
    // $this->db->insert('reset_tokens', ['user_id' => $userId, 'token' => $token, 'created_at' => date('Y-m-d H:i:s')]);
}


/**
 * Sends an email.  Replace this with your email sending implementation.
 * @param string $to
 * @param string $subject
 * @param string $body
 * @param string $headers
 * @return bool
 */
function send_email(string $to, string $subject, string $body, string $headers): bool
{
    // *** IMPLEMENT THIS FUNCTION ***
    // Example (using a placeholder):
    // error_log("Sending email to: " . $to . " Subject: " . $subject); //Log for debugging
    // return true; //Replace with your actual email sending code.

    //Real Email sending example - using PHPMailer (install via Composer)
    require_once 'vendor/phpmailer/phpmailer/src/Exception.php';
    require_once 'vendor/phpmailer/phpmailer/src/Exception.php';

    $mail = new \PHPMailer\PHPMailer\PHPMailer();
    $mail->isSMTP();
    $mail->Host = 'smtp.example.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'your_smtp_username';
    $mail->Password = 'your_smtp_password';
    $mail->Port = 587;
    $mail->SMART_HOST = true;
    $mail->setFrom('your_email@example.com', 'Your Website Name');
    $mail->addAddress($to, 'User Name');
    $mail->Subject = $subject;
    $mail->Body = $body;
    $mail->isHTML(false); // Set to true if you're sending HTML emails
    if ($mail->send()) {
        return true;
    } else {
        error_log("Error sending email: " . print_r($mail->getSMTPError(), true));
        return false;
    }

}


/**
 * Returns the base URL of your website.  This is how the reset URL will be constructed.
 * @return string
 */
function base_url(): string
{
    // Replace with your actual base URL.
    return 'http://localhost/your_website'; //Example.
}


/**
 * Returns the admin email address.
 * @return string
 */
function get_admin_email(): string {
    return 'admin@your_website.com';
}


?>
