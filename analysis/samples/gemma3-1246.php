    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required>
    <button type="submit">Reset Password</button>
  </form>

</body>
</html>


<?php

// Assuming you have a database connection established and a 'users' table
// with a 'email' column.  Replace these with your actual credentials and table name.

// Database credentials
$host = "localhost";
$username = "your_db_username";
$password = "your_db_password";
$dbname = "your_db_name";

// Security - IMPORTANT:  Never store passwords in plain text.  Use a strong hashing algorithm.
// This is a placeholder for demonstration purposes only.  Implement proper password hashing!
// Ideally, use password_hash() to securely hash the new password before updating the database.

// Function to send an email
function sendPasswordResetEmail($email, $resetToken) {
    // Replace with your email sending code.
    // This is a placeholder.  Use a proper email library like PHPMailer.
    echo "Sending password reset email to: " . $email . " with token: " . $resetToken . "
";
    // Example using a simple placeholder
    // $subject = "Password Reset";
    // $body = "Click this link to reset your password: " . URL . "/reset_password.php?token=" . $resetToken;
    // $headers = "From: your_email@example.com\r
";
    // mail($email, $subject, $body, $headers);
}


// Forgot Password Function
function forgotPassword($email) {
    // Validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return "Invalid email address.";
    }

    // Check if the user exists in the database
    $conn = new mysqli($host, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "SELECT id, password, email FROM users WHERE email = '$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $userId = $user['id'];
        $hashedPassword = $user['password'];  //Get the hashed password. DO NOT STORE THIS IN PLAIN TEXT!
        // Generate a unique, time-based token for the reset link
        $resetToken = bin2hex(random_bytes(32));  // 32 bytes (256 bits) for security

        // Store the token in the database associated with the user.
        $sql = "UPDATE users SET reset_token = '$resetToken' WHERE id = '$userId'";
        if ($conn->query($sql) === TRUE) {
            // Send the reset email
            sendPasswordResetEmail($email, $resetToken);

            return "Password reset email sent to your email address. Please check your inbox.";
        } else {
            echo "Error updating reset token: " . $conn->error;
            return "An error occurred while sending the reset email.";
        }
    } else {
        return "User not found.";
    }

    $conn->close();
}


// Example Usage (for testing)
// $emailToReset = "test@example.com"; // Replace with a valid email address for testing.
// $result = forgotPassword($emailToReset);
// echo $result;
?>
