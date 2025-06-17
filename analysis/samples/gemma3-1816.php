

<?php

// Assuming you have a database connection established and a 'users' table
// with a 'email' column and potentially a 'password' column.

function forgot_password($email, $reset_token, $expiry_time = 3600) { //default expiry of 1 hour

  // 1. Validate Email
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    return "Invalid email address.";
  }

  // 2. Check if a reset token already exists for this email
  $query = "SELECT id, token, created_at FROM reset_tokens WHERE email = ? AND token = ? AND expiry_time > NOW()";
  $stmt = $conn->prepare($query);
  $stmt->bind_param("ss", $email, $reset_token);
  $stmt->execute();

  if ($stmt->num_rows > 0) {
    // Token exists, proceed with password reset
    //  Ideally, you'd update the token's expiry time here 
    //  to force a new reset link to be generated.  For simplicity, we'll just
    //  return the token.
    $result = $stmt->fetch_assoc();
    return $result['token']; // Or return the entire result array if needed
  } else {
    // Token does not exist
    return "Invalid reset token.  Please request a new one.";
  }
}



// Example Usage (assuming $conn is your database connection)
//  This is just for testing; in a real application, you would
//  handle the form submission and user interaction.

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $email = $_POST["email"];
  $reset_token = $_POST["reset_token"];

  $new_reset_token = forgot_password($email, $reset_token);

  if ($new_reset_token == "Invalid reset token.  Please request a new one.") {
    echo "<p style='color: red;'>$new_reset_token</p>";
  } else {
      echo "<p style='color: green;'>Reset token: $new_reset_token.  Please use this in the password reset form.</p>";
      // In a real application, you would send an email with this token.
  }

}
?>
