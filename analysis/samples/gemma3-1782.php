

<?php

// Assuming you have a database connection established (e.g., $pdo)

function forgot_password($email, $token, $reset_password_link) {
  // 1. Validate Email and Token
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    return "Invalid email format.";
  }

  if (empty($token)) {
    return "Invalid or missing token.";
  }

  // 2. Check if the token exists in the database for the given email
  $stmt = $pdo->prepare("SELECT id FROM users WHERE email = :email AND token = :token");
  $stmt->bindParam(':email', $email);
  $stmt->bindParam(':token', $token);
  $stmt->execute();

  if ($stmt->rowCount() == 0) {
    return "Invalid token or token does not exist.";
  }

  // 3.  Set token expiration (optional, but highly recommended)
  //  This prevents tokens from being used indefinitely
  $expiry_time = time() + (3600 * 24 * 7);  // Token expires in 7 days
  //  You would likely store the expiry_time in the database along with the token
  //  so you can check it when validating the token.  For this example, we'll
  //  assume the expiry is handled on the application side.


  // 4. Create a reset password link (generate a new token)
  $new_token = bin2hex(random_bytes(32));  // Generate a strong, random token
  $stmt = $pdo->prepare("UPDATE users SET token = :new_token, token_expiry = :expiry_time WHERE email = :email");
  $stmt->bindParam(':email', $email);
  $stmt->bindParam(':new_token', $new_token);
  $stmt->bindParam(':expiry_time', $expiry_time);
  $stmt->execute();

  // 5.  Return the reset password link.
  //  You'll need to format this link for display on your website.
  $reset_url = $reset_password_link . "?token=" . $new_token;

  return $reset_url;
}


// Example Usage (Illustrative - replace with your actual logic)
// Assuming you have a database connection $pdo
// $email = $_POST['email']; // Get email from form
// $token = $_POST['token'];  // Get token from form (e.g., from a POST request)

// if (isset($email) && isset($token)) {
//   $reset_url = forgot_password($email, $token, $reset_password_link);
//   echo $reset_url; // Display the reset password link
// } else {
//   echo "Please provide email and token.";
// }



?>
