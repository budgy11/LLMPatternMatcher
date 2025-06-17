

<?php

// Database connection details (replace with your actual credentials)
$host = "localhost";
$username = "your_db_username";
$password = "your_db_password";
$database = "your_db_name";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // Get the email from the form
  $email = trim($_POST["email"]);

  // Validate the email format
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $error = "Invalid email format.";
  } else {
    //  *** IMPORTANT SECURITY NOTE: ***
    //  In a production environment, you MUST hash the password before
    //  storing it in the database.  This example uses a simple string for demonstration.
    //  Never store plain text passwords.

    // Simulate password reset process (replace with actual database query)
    $reset_token = generate_unique_token(); // Generates a secure random string
    $sql = "UPDATE users SET reset_token = '$reset_token' WHERE email = '$email'";

    // Execute the query
    $result = mysqli_query($conn, $sql);

    if (mysqli_affected_rows($conn) > 0) {
      // Send an email with a reset link
      $subject = "Password Reset Link";
      $to = $email;
      $link = "http://yourwebsite.com/reset_password.php?token=$reset_token"; // Replace with your website URL
      $message = "Click the link above to reset your password.";
      $headers = "From: your_email@example.com";
      mail($to, $message, $headers);

      $success = "Password reset link has been sent to your email address.";
    } else {
      $error = "An error occurred while processing your request.";
    }
  }
}

// Function to generate a unique token
function generate_unique_token() {
  return bin2hex(random_bytes(32));
}


// Start the HTML page
?>
