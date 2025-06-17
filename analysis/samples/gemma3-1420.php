

<?php

// Database connection details (replace with your actual credentials)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_db_user";
$db_password = "your_db_password";

// Function to handle password reset
function forgotPassword($email)
{
    // 1. Check if the email exists
    $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "SELECT id, email FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email); // 's' indicates a string parameter
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        $stmt->close();
        return false; // Email not found
    }

    $user = $result->fetch_assoc();
    $user_id = $user['id'];
    $user_email = $user['email'];

    $stmt->close();

    // 2. Generate a unique token
    $token = bin2hex(random_bytes(32));  // Create a strong, random token

    // 3. Hash the token
    $hashed_token = password_hash($token, PASSWORD_DEFAULT);

    // 4.  Store the token in the database
    $sql = "UPDATE users SET reset_token = ? , reset_token_expiry = NOW() WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $hashed_token, $user_email);
    $stmt->execute();

    if (!$stmt->affected_rows > 0) {
        $stmt->close();
        return false; // Failed to store token
    }

    $stmt->close();

    // 5. Send the password reset email (implementation omitted for brevity)
    //  - You'll need to implement the logic to send an email
    //    containing a link with the token.  This typically involves
    //    using a library like PHPMailer or similar.
    //  - Example (placeholder):
    //  $email_content = "Click <a href='reset_password.php?token=$token'>here</a> to reset your password.";
    //  sendEmail($user_email, "Reset Your Password", $email_content);


    return true; // Token generation and storage successful
}



// Example Usage (This is just an example.  Don't use this directly in a production environment)
// $email_to_reset = "testuser@example.com";  // Replace with a valid email
// if (forgotPassword($email_to_reset)) {
//     echo "Password reset email sent to " . $email_to_reset;
// } else {
//     echo "Failed to generate password reset token.";
// }



?>
