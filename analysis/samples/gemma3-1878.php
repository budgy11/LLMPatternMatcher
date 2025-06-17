

<?php

// Assuming you have a database connection established
// Replace 'your_db_connection' with your actual connection details
$db = new mysqli('localhost', 'username', 'password', 'database_name');

// Check connection
if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}

/**
 * Forgets a user's password and sends a password reset email.
 *
 * @param string $email The user's email address.
 * @return bool True if email was sent, false otherwise.
 */
function forgot_password($email) {
  // Sanitize the email to prevent potential security issues
  $email = filter_var($email, FILTER_SANITIZE_EMAIL);

  // Validate email format
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    return false; // Invalid email format
  }

  // Check if the user exists
  $result = $db->query("SELECT id, username FROM users WHERE email = '$email'");

  if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    $userId = $user['id'];
    $username = $user['username'];

    // Generate a unique, random password reset token
    $reset_token = bin2hex(random_bytes(32));

    // Store the token in the database for the user
    $db->query("UPDATE users SET reset_token = '$reset_token' WHERE id = $userId");

    // Prepare a password reset email
    $to = $email;
    $subject = 'Password Reset';
    $message = "Click this link to reset your password: " . '<a href="' . $_SERVER['PHP_SELF'] . '?reset=' . urlencode($reset_token) . '"' . ' target="_blank">' . '<a href="' . $_SERVER['PHP_SELF'] . '?reset=' . urlencode($reset_token) . '"' . ' target="_blank">' . $_SERVER['PHP_SELF'] . '?reset=' . urlencode($reset_token) . '</a>';
    $headers = "From: your_email@example.com" . "\r
";
    mail($to, $message, $headers);

    return true; // Email sent successfully
  } else {
    return false; // User not found
  }
}


// Example Usage (This is for demonstration - you'll need to handle form submission and validation)
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    // Get the email from the form (assuming a form submitted)
    $email = $_POST['email']; // Get the email from the form
    if (forgot_password($email)) {
        echo "Password reset email has been sent to " . $email;
    } else {
        echo "Email not found or an error occurred.";
    }
}

// Prevent direct access to this script
if ($_SERVER["REQUEST_METHOD"] != "GET") {
  header("Location: index.php"); // Redirect to a welcome page or login form.
}


?>
