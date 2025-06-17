

<?php

// Assuming you have a database connection established (e.g., $db)

/**
 * Resets a user's password.
 *
 * @param string $email The email address of the user requesting password reset.
 * @return string  'Password reset email sent.' or error message.
 */
function forgot_password(string $email)
{
    global $db;  // Ensure the database connection is accessible

    // Sanitize the email input (important for security)
    $email = filter_var($email, FILTER_SANITIZE_EMAIL);

    // Validate the email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return "Invalid email address.";
    }

    // Check if the user exists in the database
    $stmt = $db->prepare("SELECT id, username FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        return "User not found.";
    }

    // Generate a unique token and store it in the database
    $token = bin2hex(random_bytes(32));
    $stmt = $db->prepare("INSERT INTO password_resets (user_id, token, expiry) VALUES (?, ?, ?)");
    $stmt->execute([$user['id'], $token, time() + (24 * 60 * 60)]); // Token expires in 24 hours

    // Send the password reset email
    $to = $email;
    $subject = 'Password Reset';
    $message = "Click the link below to reset your password: " . urlencode("http://" . $_SERVER['SERVER_NAME'] . "/reset_password?token=" . $token);
    $headers = "From: yourwebsite@example.com\r
"; // Replace with your email address
    mail($to, $subject, $message, $headers);

    return "Password reset email sent. Please check your inbox.";
}


// Example Usage (Simulated)
//  In a real application, this would be triggered from a form submission.

//  For testing, you could directly call the function:
//  $result = forgot_password('test@example.com');
//  echo $result;

?>
