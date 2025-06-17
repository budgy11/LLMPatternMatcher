

<?php

// Database connection details (replace with your actual credentials)
$host = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "your_database_name";

// Function to handle the forgot password process
function forgot_password($email) {
    // 1. Validate Email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return "Invalid email format.";
    }

    // 2. Fetch User Data
    $conn = new mysqli($host, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "SELECT id, username FROM users WHERE email = '$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $user_data = $result->fetch_assoc();
        $userId = $user_data['id'];
        $username = $user_data['username'];

        // 3. Generate a Unique Token
        $token = bin2hex(random_bytes(32)); // Generate a cryptographically secure random token

        // 4. Store Token in Database (with expiration)
        $conn->query("UPDATE users SET reset_token = '$token', reset_token_expiry = NOW() + INTERVAL 1 HOUR WHERE id = '$userId'");

        // 5. Prepare Email Content
        $to = $email;
        $subject = "Password Reset Request";
        $message = "Click the following link to reset your password: <a href='" . $_SERVER['PHP_SELF'] . "?reset_token=$token'>Reset Password</a>"; // IMPORTANT: Use $_SERVER['PHP_SELF']
        $headers = "From: your_email@example.com"; // Replace with your email address

        // 6. Send Email
        mail($to, $message, $headers);

        return "Password reset email sent to $email.";
    } else {
        return "User not found.";
    }

    $conn->close();
}

// Example Usage (Handle the reset link submission)
if (isset($_GET['reset_token'])) {
    $token = $_GET['reset_token'];

    // 1. Verify Token
    $conn = new mysqli($host, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "SELECT id, username FROM users WHERE reset_token = '$token' AND reset_token_expiry > NOW()";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $user_data = $result->fetch_assoc();
        $userId = $user_data['id'];
        $username = $user_data['username'];

        // 2.  Set a New Password (replace with your password input validation)
        $new_password = $_POST['new_password'];
        $confirm_password = $_POST['confirm_password'];

        if ($new_password == $confirm_password) {
            // Hash the new password
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

            // 3. Update User Password
            $conn->query("UPDATE users SET password = '$hashed_password' WHERE id = '$userId'");

            // 4. Delete the Token (important for security)
            $conn->query("DELETE FROM users WHERE id = '$userId' AND reset_token = '$token'");

            // 5. Redirect
            return "Password reset successful. Please log in.";

        } else {
            return "Passwords do not match.";
        }

    } else {
        return "Invalid reset token or token expired.";
    }

    $conn->close();
}
?>
