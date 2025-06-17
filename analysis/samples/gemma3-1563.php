

<?php

// Database connection details - Replace with your actual values
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_database_username";
$db_password = "your_database_password";

// Function to reset password
function forgot_password($email)
{
    // 1. Check if email exists in the database
    $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "SELECT id, email FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user_id = $result->fetch_assoc()['id'];
        $user_email = $result->fetch_assoc()['email'];

        // 2. Generate a unique token for the reset link
        $token = bin2hex(random_bytes(32)); // Generates a secure random string

        // 3. Store the token in the database associated with the user
        $sql = "UPDATE users SET password_reset_token = ? WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $token, $email);

        if ($stmt->execute() === TRUE) {
            // 4.  Send an email with the reset link
            $reset_link = "http://localhost/reset_password.php?token=" . $token; // Replace with your website URL
            $to = $email;
            $subject = "Password Reset";
            $message = "Click on the link below to reset your password: " . $reset_link;
            $headers = "From: your_email@example.com"; // Replace with your email address

            mail($to, $subject, $message, $headers);

            echo "Password reset link sent to $email";
        } else {
            echo "Error updating password reset token: " . $conn->error;
        }

    } else {
        echo "Email not found";
    }

    $stmt->close();
    $conn->close();
}

// Example Usage (Demonstration -  Don't use this directly in a production environment)
//  This is only for testing the function.  Securely validate and sanitize user inputs before using.
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    forgot_password($email);
}
?>
