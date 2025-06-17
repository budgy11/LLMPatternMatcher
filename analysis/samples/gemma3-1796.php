

<?php

// Ensure this file is protected (only accessible via HTTP GET or POST requests)
if (!isset($_SERVER['REQUEST_METHOD']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
  header("Location: /"); // Redirect to the homepage or appropriate login page
  exit;
}

// Include database connection (replace with your actual database connection)
require_once 'config.php'; // Assuming you have a config.php file

// Get the email from the request
$email = $_POST['email'];

// Validate the email format (basic validation - consider more robust validation)
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
  echo "Invalid email format.";
  exit;
}

// Check if the email already exists in the database
$stmt = $conn->prepare("SELECT id, email FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
  // Email exists, generate a token and send a password reset email
  $token = bin2hex(random_bytes(32)); // Generate a random token for security
  $reset_link = 'http://' . $_SERVER['HTTP_HOST'] . '/reset_password.php?token=' . $token;

  // Prepare the email message
  $subject = 'Password Reset';
  $message = "Click on the following link to reset your password: " . $reset_link;
  $headers = 'From: ' . $from_email;

  // Send the email (replace with your email sending function/library)
  if (send_email($email, $subject, $message, $headers)) {
    // Password reset link sent successfully
    echo "Password reset email sent to your email address.";
  } else {
    // Failed to send email
    echo "Failed to send password reset email. Please try again.";
  }
} else {
  // Email doesn't exist
  echo "Email address not found.";
}

// Clean up the database connection
$stmt->close();

?>
