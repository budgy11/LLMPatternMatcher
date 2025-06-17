

<?php

// Replace with your database connection details
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_username";
$db_password = "your_password";

// Check if the request method is POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // Get the email address from the form
  $email = $_POST["email"];

  // Validate the email address (basic validation - you should improve this)
  if (empty($email)) {
    $message = "Email address is required.";
  } else {
    // Hash the password (important for security)
    $hashed_password = password_hash("default_password", PASSWORD_DEFAULT);  // You'll need a default password for this example

    // Prepare the SQL query
    $sql = "SELECT id, email FROM users WHERE email = '$email'";

    // Execute the query
    $result = mysqli_query($db_connection, $sql);

    // Check if the query was successful
    if ($result) {
      // Check if any user was found
      if (mysqli_num_rows($result) > 0) {
        // Set the password reset token (a unique, random string)
        $reset_token = bin2hex(random_bytes(32));

        // Prepare the update query
        $update_query = "UPDATE users SET reset_token = '$reset_token' WHERE email = '$email'";

        // Execute the update query
        mysqli_query($db_connection, $update_query);

        // Send an email to the user with the reset link
        $to = $email;
        $subject = "Password Reset";
        $message = "Click on the following link to reset your password: " . "<a href='reset_password.php?token=$reset_token'>Reset Password</a>";
        $headers = "From: your_email@example.com";  // Change this to your email

        mail($to, $message, $headers);


        $message = "Password reset link has been sent to your email address.";
      } else {
        $message = "No user found with this email address.";
      }
    } else {
      $message = "Error querying the database.";
    }
  }
}

// Start the session
session_start();

// Display any error messages
if (isset($message)) {
  echo "<p style='color: red;'>$message</p>";
}

?>
