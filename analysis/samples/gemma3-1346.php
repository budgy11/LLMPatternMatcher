

<?php

// Assuming you have a database connection established (e.g., $db)

// Function to handle password reset
function forgotPassword($email) {
  // 1. Validate Email (important for security)
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    return "Invalid email address.";
  }

  // 2. Generate a Unique Token
  $token = bin2hex(random_bytes(32)); // Generates a cryptographically secure random string

  // 3.  Prepare the Reset Token and expiration time
  $expiration_time = time() + 60*60*24; // Token expires after 24 hours
  
  // 4. Prepare the Reset Request
  $reset_data = array(
    'email' => $email,
    'token' => $token,
    'expiration' => $expiration_time
  );

  // 5. Store the Reset Token in the Database - THIS IS CRUCIAL
  //  Replace with your database query logic.  This is a placeholder.
  //  Important: Use parameterized queries to prevent SQL injection.
  $query = "INSERT INTO password_resets (email, token, expiration) VALUES (?, ?, ?)";
  $stmt = $db->prepare($query);
  $stmt->bind_param("sii", $email, $token, $expiration_time);
  $stmt->execute();
  $reset_id = $db->insert_id; // Get the ID of the newly inserted row
  $stmt->close();


  // 6.  Send the Reset Email
  $subject = "Password Reset Request";
  $message = "Click on the following link to reset your password: <a href='" . $_SERVER['PHP_SELF'] . "?reset_token=" . $token . "'>Reset Password</a>";
  $headers = "From: your_email@example.com"; // Replace with your sender email
  mail($email, $subject, $message, $headers);

  // 7. Return a success message
  return "Password reset link has been sent to your email address.";
}


// --- Example Usage (for demonstration) ---

// Example 1: Successfully sending the reset link
if (isset($_GET['reset_token'])) {
    $token = $_GET['reset_token'];
    $result = forgotPassword($token);
    echo $result;
}

?>
