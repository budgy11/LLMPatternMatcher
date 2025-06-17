

<?php

// Database connection details (Replace with your actual credentials)
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database_name";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // Get the email from the form
  $email = $_POST["email"];

  // Validate the email format (basic validation - you might want to add more robust validation)
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $error = "Invalid email address.";
  } else {
    // Check if the user exists in the database
    $sql = "SELECT id, password, email FROM users WHERE email = '$email'";
    $result = mysqli_query($host, $sql);

    if ($result) {
      if (mysqli_num_rows($result) == 1) {
        // User found, generate a password reset token
        $resetToken = bin2hex(random_bytes(32)); // Generate a random token

        // Store the token in the database for the user
        $sql = "UPDATE users SET reset_token = '$resetToken' WHERE email = '$email'";
        mysqli_query($host, $sql);

        // Send the password reset email (Implementation of email sending is outside of this example)
        $to = $email;
        $subject = "Password Reset Request";
        $message = "Click on the following link to reset your password: " . $_SERVER["PHP_SELF"] . "?reset_token=$resetToken";
        $headers = "From: your_email@example.com"; // Replace with your email address

        mail($to, $message, $headers);

        $error = "Password reset email sent to $email";
      } else {
        $error = "User not found.";
      }
    } else {
      $error = "Error querying the database.";
    }
  }
}
?>
