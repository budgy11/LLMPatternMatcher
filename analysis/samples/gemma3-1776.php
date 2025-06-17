

<?php

// Database connection (Replace with your actual credentials)
$host = "localhost";
$username = "your_db_username";
$password = "your_db_password";
$database = "your_database_name";

// Function to handle password reset
function forgot_password($email)
{
  // 1. Check if the email exists in the database
  $conn = new mysqli($host, $username, $password, $database);

  if ($conn->connect_error) {
    // Handle the connection error. Log it or display a user-friendly message.
    error_log("Failed to connect to database: " . $conn->connect_error);
    return false; 
  }

  $query = "SELECT id, email FROM users WHERE email = ?";
  $stmt = $conn->prepare($query);
  $stmt->bind_param("s", $email);  // "s" indicates a string parameter
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows == 0) {
    // Email doesn't exist
    $stmt->close();
    $conn->close();
    return false;
  }

  $user = $result->fetch_assoc();
  $userId = $user['id'];
  $stmt->close();

  // 2. Generate a unique token
  $token = bin2hex(random_bytes(32));

  // 3. Store the token in the database
  $query = "UPDATE users SET reset_token = ? WHERE id = ?";
  $stmt = $conn->prepare($query);
  $stmt->bind_param("ss", $token, $userId);
  $stmt->execute();

  if (!$stmt->affected_rows > 0) {
    // Error setting token.  Handle appropriately (logging, error display).
    $stmt->close();
    $conn->close();
    return false;
  }

  // 4.  Send an email to the user with the reset link
  $to = $email;
  $subject = "Password Reset";
  $message = "Click the link below to reset your password: " . $_SERVER['PHP_SELF'] . "?reset_token=" . $token;
  $headers = "From: your_email@example.com"; // Replace with your email address

  mail($to, $subject, $message, $headers);

  // 5. Close the connection
  $conn->close();

  return true;
}

// Example usage (for testing - this would typically be a form submission)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $email = $_POST["email"];
  if (empty($email)) {
    echo "Please enter your email address.";
  } else {
    if (forgot_password($email)) {
      echo "Password reset link sent to your email address.";
    } else {
      echo "An error occurred while sending the password reset link.";
    }
  }
}

?>
