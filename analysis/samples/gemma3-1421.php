

<?php

// Assuming you have a database connection established (e.g., $db)

/**
 * Resets a user's password.
 *
 * @param string $email The user's email address.
 * @return string "Password reset email sent." or "Invalid email address or user not found."
 */
function forgot_password(string $email): string
{
    // 1. Validate email format (basic check)
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return "Invalid email address.";
    }

    // 2.  Check if the user exists in the database.
    $user = getUserByEmail($email); // Function to retrieve user by email

    if ($user === null) {
        return "Invalid email address or user not found.";
    }

    // 3. Generate a unique token for password reset.  Use a cryptographically secure random function
    $token = generate_unique_token();

    // 4. Store the token associated with the user's email in the database (or cache).
    //    This is crucial!  It allows us to verify that the user actually requested the reset.
    save_token_to_database($user->id, $token);

    // 5.  Send the password reset email.  (Implement your email sending logic here).
    $subject = "Password Reset Request";
    $message = "Please click the following link to reset your password: " . $base_url . "/reset_password?token=" . $token;
    $headers = "From: " . $from_email . "\r
";
    // Use a secure method to send email (e.g., PHPMailer or similar)
    // $result = send_email($to_email, $subject, $message, $headers);

    //Simulate email sending for demonstration purposes.
    $result = "Password reset email sent.";

    return $result;
}


/**
 *  Helper function to retrieve a user by email.
 *  Replace this with your actual database query.
 *
 * @param string $email The email address to search for.
 * @return User|null The User object if found, null otherwise.
 */
function getUserByEmail(string $email): ?User
{
    // This is a placeholder.  Adapt to your database.
    // Example using a hypothetical User class:
    $user = new User(); // Create a User object
    // Replace this with your actual database query.  For example:
    // $result = mysqli_query($db, "SELECT * FROM users WHERE email = '$email'");
    // if (mysqli_num_rows($result) > 0) {
    //   $user = new User();
    //   $user->id = mysqli_fetch_assoc($result)['id'];
    //   $user->email = mysqli_fetch_assoc($result)['email'];
    //   //... other user fields...
    // }
    return null;  // User not found
}



/**
 * Generates a unique token.  Use a cryptographically secure random function.
 *
 * @return string A unique token.
 */
function generate_unique_token(): string
{
    return bin2hex(random_bytes(32)); // Use random_bytes for a secure random string.
}

/**
 *  Saves the token associated with the user's ID in the database.
 *  Replace this with your actual database query.
 *
 * @param int $userId The user's ID.
 * @param string $token The token to store.
 */
function save_token_to_database(int $userId, string $token): void
{
    // Replace this with your actual database query.
    // Example using a hypothetical User table:
    // $sql = "UPDATE users SET reset_token = '$token', reset_token_expiry = NOW() WHERE id = $userId";
    // mysqli_query($db, $sql);
}


/**
 *  (Placeholder) Send email function.  Implement with a secure email library.
 */
function send_email(string $to_email, string $subject, string $message, string $headers): string {
    // Replace this with your actual email sending logic.
    // Use a secure library like PHPMailer.
    // Example:
    // $mail = new PHPMailer(true);
    // $mail->SMTPDebugEnable = false;
    // $mail->isSMTP();
    // $mail->Host = 'smtp.example.com';
    // $mail->Port = 587;
    // $mail->Username = 'your_email@example.com';
    // $mail->Password = 'your_password';
    // $mail->SetFrom('your_email@example.com', 'Your Name');
    // $mail->addAddress($to_email);
    // $mail->Subject = $subject;
    // $mail->Body = $message;
    // $mail->AltBody = $message;
    // $mail->CharSet = 'UTF-8';
    // $mail->send();
    return "Password reset email sent.";
}




// Example Usage (simulated)
//  You'd typically get the email from a form submission.
$email = "testuser@example.com";
$result = forgot_password($email);
echo $result . "
";



//  Dummy User class (replace with your actual User class)
class User {
    public ?int $id = null;
    public string $email = '';
}
?>
