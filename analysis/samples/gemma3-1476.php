

<?php

// Database connection details - Replace with your actual values
$host = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "your_database_name";

// Function to handle password reset
function forgotPassword($email) {
    // 1. Generate a unique, secure token
    $token = bin2hex(random_bytes(32));

    // 2. Create a temporary password reset link
    $resetLink = "/reset_password.php?token=" . $token . "&email=" . urlencode($email);

    // 3.  Prepare the SQL query
    $query = "INSERT INTO password_resets (email, token, expires) VALUES ('" . $email . "', '" . $token . "', NOW() + INTERVAL 1 HOUR)"; 

    // 4.  Database connection
    $conn = new mysqli($host, $username, $password, $dbname);

    // 5. Check for connection errors
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // 6. Execute the query
    if ($conn->query($query) === TRUE) {
        // 7. Send email (Implementation needed - See below for an example)
        sendResetPasswordEmail($email, $resetLink);
        echo "Password reset email sent to " . $email;
    } else {
        echo "Error creating password reset link: " . $conn->error;
    }

    // 8. Close the connection
    $conn->close();
}


// Function to send the password reset email (Placeholder - Replace with your actual email sending logic)
function sendResetPasswordEmail($email, $resetLink) {
    // This is a placeholder.  You'll need to replace this with your email sending code.
    // This example just prints the email link to the console.

    // In a real application, you'd use a library like PHPMailer or SwiftMailer
    // to send the email.  Make sure you configure your email settings correctly.

    echo "<br>Password reset link: <a href='" . $resetLink . "'>Click here to reset your password</a>";
}

// Example usage:  (Call this function with the user's email address)
// You'd typically get the email from a form submission.

// Example - For demonstration purposes ONLY.  Do NOT use this in a production environment!
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    if (!empty($email)) {
        forgotPassword($email);
    } else {
        echo "Please enter your email address.";
    }
}


?>
