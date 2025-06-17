

<?php

// Assuming you have a database connection established and named 'conn'

// Function to handle password reset requests
function forgot_password($email) {
  // 1. Validate Email
  $email = filter_var($email, FILTER_SANITIZE_EMAIL); // Sanitize email
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    return "Invalid email address.";
  }

  // 2. Check if the email exists in the database
  $query = "SELECT id, user_id FROM users WHERE email = '$email'";
  $result = mysqli_query($conn, $query);

  if ($result) {
    $row = mysqli_fetch_assoc($result);
    if ($row) {
      // 3. Generate a unique token
      $token = bin2hex(random_bytes(32)); // Use bin2hex for better security

      // 4. Hash the token for security
      $hashed_token = password_hash($token, PASSWORD_DEFAULT);

      // 5. Store the token in the database for the user
      $query = "UPDATE users SET reset_token = '$hashed_token' WHERE email = '$email'";
      if (mysqli_query($conn, $query)) {
        return "Password reset email sent.  Please check your inbox.";
      } else {
        return "Error updating token in database.";
      }
    } else {
      return "User not found.";
    }
  } else {
    return "Database error.";
  }
}

// Example Usage (Illustrative - Integrate into your application's logic)
// $email = $_POST['email']; // Get email from form submission
// $reset_message = forgot_password($email);
// echo $reset_message;

?>
