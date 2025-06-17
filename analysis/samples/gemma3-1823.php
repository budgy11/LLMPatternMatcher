

<?php

// Assuming you have a database connection established and a 'users' table with an 'email' field
// and a 'password' field.  This example uses a simple username/password approach,
// but in a real application, you'd likely use hashing and salting for security.

// Database connection details - Replace with your actual values
$db_host = 'localhost';
$db_name = 'your_database_name';
$db_user = 'your_username';
$db_password = 'your_password';

/**
 * Resets a user's password using email.
 *
 * @param string $email The email address of the user.
 * @return string  A message indicating success or failure.
 */
function forgot_password($email) {
    // Validate email format (basic check - improve for production)
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return "Invalid email address.";
    }

    // Check if the user exists
    $stmt = $GLOBALS['conn']->prepare("SELECT id, username FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        $stmt->close();
        return "User not found.";
    }

    $user = $result->fetch_assoc();
    $stmt->close();

    // Generate a temporary password (for demo purposes - use a cryptographically secure method in production)
    $temp_password = 'TempPassword123';  // Replace with a more robust method

    // Send the password reset email (This is a placeholder - implement your email sending logic here)
    $to = $email;
    $subject = 'Password Reset';
    $message = "To reset your password, please click on this link: " .  $_SERVER['PHP_SELF'] . "?reset=" . urlencode($temp_password);
    $headers = "From: your_email@example.com" . "\r
";

    if (mail($to, $subject, $message, $headers)) {
        return "Password reset link sent to your email address.  Check your inbox.";
    } else {
        return "Failed to send password reset email. Please try again.";
    }
}

// Example Usage (this part would typically be from a form submission)
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    // Get the email from the URL parameter (for testing purposes)
    $email = $_GET["email"];

    // Call the forgot_password function
    $result = forgot_password($email);

    // Display the result
    echo "<p>" . $result . "</p>";
} else {
    // Handle POST request (if the form is submitted)
    $email = $_POST["email"];
    $result = forgot_password($email);
    echo "<p>" . $result . "</p>";
}

?>
