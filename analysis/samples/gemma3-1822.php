  <label for="email">Email:</label>
  <input type="email" id="email" name="email" required>
  <button type="submit">Request Password Reset</button>
</form>


<?php

// Ensure this file is protected to prevent direct access to the database.
// You should also use a more secure method for generating and sending
// the reset token (e.g., using a library for cryptographically secure random numbers).

// Database connection details (Replace with your actual credentials)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_db_user";
$db_password = "your_db_password";

// Function to generate a unique token
function generateUniqueToken() {
    return bin2hex(random_bytes(32)); // Generates a 32-byte (256-bit) random binary string
}

// Function to send the reset password email (Requires an email setup)
function sendResetPasswordEmail($email, $token) {
    // Implement your email sending logic here.
    // This is just a placeholder.  You'll need to use a library or
    // your email server to actually send the email.

    // Example (replace with your email sending code):
    $subject = "Password Reset";
    $message = "Click on the following link to reset your password: " . "<a href='" . $_SERVER["PHP_SELF"] . "?reset=true&token=" . $token . "'>Reset Password</a>";

    // In a real application, you'd use a library like PHPMailer or SwiftMailer.
    // Example using PHPMailer (requires installation):
    // $mail = new PHPMailer(true);
    // $mail->isSMTP();
    // $mail->Host = 'smtp.example.com';
    // $mail->Port = 587;
    // $mail->Username = 'your_email@example.com';
    // $mail->Password = 'your_password';
    // $mail->SetFrom('your_email@example.com', 'Your Application Name');
    // $mail->Subject = $subject;
    // $mail->Body = $message;
    // $mail->AltBody = $message;  // For email clients that don't support HTML
    // $mail->Send();

    echo "Reset email sent to $email.  Check your inbox!"; // Placeholder
}


// Function to reset the password
function resetPassword($email, $token) {
    // 1. Verify the token
    $query = "SELECT * FROM users WHERE email = '$email' AND token = '$token'";
    $result = mysqli_query($GLOBALS['db_conn'], $query);

    if (mysqli_num_rows($result) > 0) {
        // Token is valid, proceed with password reset

        // 2.  Generate a new, strong password (example)
        $new_password = generateStrongPassword(); // Replace with your password generation function
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

        // 3.  Update the user's password in the database
        $update_query = "UPDATE users SET password = '$hashed_password', token = '', last_reset = NOW() WHERE email = '$email'";
        mysqli_query($GLOBALS['db_conn'], $update_query);

        echo "Password reset successful! Please check your email for instructions.";

    } else {
        echo "Invalid token or user not found.";
    }
}


//  ------------------  Example Usage (Simplified for demonstration)  ------------------

// Check if the reset link is clicked
if (isset($_GET['reset']) && isset($_GET['token'])) {
    $email = $_GET['email']; // Potentially add validation here
    $token = $_GET['token'];

    // Validate email (simple example - improve this!)
    if (empty($email) || empty($token)) {
        echo "Error: Email and token are required.";
    } else {
        resetPassword($email, $token);
    }
}

//  ------------------  Database Connection  ------------------
$GLOBALS['db_conn'] = mysqli_connect($db_host, $db_user, $db_password, $db_name);

if (mysqli_connect_error()) {
    die("Connection failed: " . mysqli_connect_error());
}

// Add other necessary database setup/validation here, such as checking if the user exists

?>
