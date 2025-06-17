

<?php

// **Important Security Note:**  This is a simplified example for educational purposes.
// In a production environment, you **MUST** implement much stronger security measures 
// to prevent abuse and account compromise.  Consider using a dedicated password reset library,
// and employ robust rate limiting, email verification, and other security best practices.

// Database connection details (replace with your actual credentials)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_db_user";
$db_pass = "your_db_password";

// 1.  Retrieve User Information
function forgotPassword($email) {
  global $db_host, $db_name, $db_user, $db_pass;

  // Connect to the database
  $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

  if ($conn->connect_error) {
    return false; // Handle database connection error
  }

  // Prepare and execute the query to find the user
  $stmt = $conn->prepare("SELECT id, username, password_reset_token, email FROM users WHERE email = ?");
  $stmt->bind_param("s", $email); // "s" indicates a string parameter
  $stmt->execute();

  $result = $stmt->get_result();

  // Check if the user exists
  if ($result->num_rows == 0) {
    $stmt->close();
    $conn->close();
    return false; // User not found
  }

  $user = $result->fetch_assoc();
  $stmt->close();

  // 2. Generate a Unique Password Reset Token
  $token = bin2hex(random_bytes(32)); // Generate a cryptographically secure random token

  // 3.  Update the User Record with the Token
  $update_stmt = $conn->prepare("UPDATE users SET password_reset_token = ?, expiration = NOW() + INTERVAL 24 HOUR WHERE email = ?"); //Expiration after 24 hours
  $update_stmt = $conn->prepare("UPDATE users SET password_reset_token = ?, expiration = NOW() + INTERVAL 24 HOUR WHERE email = ?");
  $update_stmt->bind_param("ss", $token, $user['id']); //'ss' for two string parameters
  $update_stmt->execute();
  if (!$update_stmt->affected_rows > 0) {
    return false; // Update failed
  }


  // 4.  Send the Password Reset Link (Email)
  $to = $user['email'];
  $subject = 'Password Reset Link';
  $message = "Click this link to reset your password: " . $_SERVER['PHP_SELF'] . "?reset_token=" . urlencode($token);
  $headers = "From: your_email@example.com"; // Replace with your email address

  mail($to, $subject, $message, $headers);

  // Return true to indicate success
  return true;
}


// Example Usage (This would typically be handled via a form submission)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $email = $_POST["email"];

  if (empty($email)) {
    echo "Error: Email cannot be empty.";
  } else {
    if (forgotPassword($email)) {
      echo "Password reset email has been sent to $email.";
    } else {
      echo "Failed to reset password. Please try again.";
    }
  }
}


// HTML Form for Sending the Request (for testing)
?>
