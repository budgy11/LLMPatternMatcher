

<?php

// Assuming you have a database connection established (e.g., $db)
// and that you have a 'users' table with a 'email' column.

// Function to handle password reset requests
function forgot_password($email) {
  // Validate input - Important for security!
  if (empty($email)) {
    return 'Invalid email address.';
  }

  // Sanitize the email address (prevent SQL injection)
  $email = filter_var($email, FILTER_SANITIZE_EMAIL);

  // Check if the email exists in the database
  $result = mysqli_query($db, "SELECT id, password_reset_token FROM users WHERE email = '$email'");

  if (mysqli_num_rows($result) > 0) {
    // Generate a unique password reset token
    $token = bin2hex(random_bytes(32)); // Use cryptographically secure random bytes

    // Insert the token into the database for this user
    mysqli_query($db, "UPDATE users SET password_reset_token = '$token' WHERE email = '$email'");

    // Store the token in a session or temporary table for later use (more secure than storing in a session)
    //  For this example, we'll use a temporary table
    mysqli_query($db, "CREATE TEMPORARY TABLE IF NOT EXISTS reset_tokens (id INT, email VARCHAR(255), token VARCHAR(255))");
    mysqli_query($db, "INSERT INTO reset_tokens (id, email, token) VALUES (1, '$email', '$token')");


    // Return a success message and instructions
    return 'We have sent a password reset link to your email address.  ' .
           'Please check your inbox and follow the instructions in the email.';

  } else {
    // Email doesn't exist
    return 'Email address not found.';
  }
}


// Example usage (in a form or API endpoint):
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $reset_message = forgot_password($email);
    echo $reset_message; // Display the message
}

?>
