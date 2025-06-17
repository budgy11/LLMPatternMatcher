

<?php

// Database connection details (replace with your actual details)
$host = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "your_database";

// Function to handle password reset
function forgot_password($email)
{
    // 1. Check if the email exists in the database
    $conn = new mysqli($host, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "SELECT id, email FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email); // "s" indicates a string
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        $stmt->close();
        $conn->close();
        return false; // Email not found
    }

    $user = $result->fetch_assoc();
    $userId = $user['id'];
    $userEmail = $user['email'];
    $stmt->close();

    // 2. Generate a unique token
    $token = bin2hex(random_bytes(32));

    // 3. Store the token and user ID in the database
    $sql = "UPDATE users SET reset_token = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $token, $userId);
    $stmt->execute();

    if (!$stmt->affected_rows > 0) {
        $stmt->close();
        $conn->close();
        return false; // Failed to store the token
    }

    // 4.  Send an email with the reset link
    $to = $email;
    $subject = "Password Reset Link";
    $message = "Click this link to reset your password: " . "<a href='" . $_SERVER["PHP_SELF"] . "?reset=" . urlencode($token) . "'>Reset Password</a>";
    $headers = "From: your_email@example.com"; // Replace with your email address

    mail($to, $subject, $message, $headers);

    // 5. Close the connection
    $conn->close();

    return true; // Password reset link sent successfully
}


// Example Usage (This should be in a separate file or part of a form)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];

    if (empty($email)) {
        echo "Error: Please enter your email address.";
    } else {
        if (forgot_password($email)) {
            echo "Password reset link has been sent to your email address.";
        } else {
            echo "Error: Failed to send password reset link. Please try again.";
        }
    }
}
?>
