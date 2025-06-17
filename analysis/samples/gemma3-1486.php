
<!DOCTYPE html>
<html>
<head>
    <title>Forgot Password</title>
</head>
<body>

    <h1>Forgot Password</h1>

    <form method="post">
        <label for="email">Enter your email address:</label>
        <input type="email" id="email" name="email" required>
        <button type="submit">Reset Password</button>
    </form>

</body>
</html>


<?php

/**
 * Function to handle password reset requests.
 *
 * This function generates a unique token, emails the user with a reset link,
 * and stores the token in the database.
 *
 * @param string $email The email address of the user.
 * @return bool True if the reset link was generated and sent, false otherwise.
 */
function forgot_password(string $email): bool
{
    // 1. Generate a Unique Token
    $token = bin2hex(random_bytes(32)); // Use a strong random byte generator

    // 2. Prepare the Reset Data
    $reset_data = [
        'email' => $email,
        'token' => $token,
        'expiry' => time() + 3600 // Token expires in 1 hour (3600 seconds)
    ];

    // 3. Store the Reset Data in the Database
    // Assuming you have a database connection established as $db
    $db = new PDO("mysql:host=localhost;dbname=your_database_name", "your_username", "your_password"); // Replace with your database details
    try {
        $stmt = $db->prepare("INSERT INTO password_resets (email, token, expiry) VALUES (?, ?, ?)");
        $stmt->execute([$email, $token, $expiry]);
    } catch (PDOException $e) {
        error_log("Error creating password reset entry: " . $e->getMessage());
        // Handle database errors appropriately (e.g., log the error, display a generic error message to the user)
        return false;
    }

    // 4. Send the Reset Email
    $subject = "Password Reset Request";
    $headers = "From: your_email@example.com"; // Replace with your email address
    $message = "Click on the following link to reset your password: <a href='" . $_SERVER['PHP_SELF'] . "?reset=" . $token . "'>Reset Password</a>";

    if (mail($email, $subject, $message, $headers)) {
        return true;
    } else {
        error_log("Failed to send password reset email to " . $email);
        // Handle email sending failures appropriately (e.g., log the error, display a generic error message to the user)
        return false;
    }
}

// Example Usage (for testing - don't put this in your actual application)
// $email = "test@example.com"; // Replace with a valid email address
// if (forgot_password($email)) {
//     echo "Password reset email sent to " . $email . ". Please check their inbox.";
// } else {
//     echo "Failed to generate password reset link.";
// }

?>
