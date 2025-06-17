

<?php

// Assuming you have a database connection established and stored in a variable like $db
// and that you have a 'users' table with an 'email' column.

function forgot_password($email, $reset_token, $expiry_time = 3600) { // $expiry_time defaults to 1 hour (3600 seconds)

  // 1. Check if the email exists in the database
  $query = "SELECT id FROM users WHERE email = ?";
  $stmt = $db->prepare($query);
  $stmt->bind_param("s", $email); // 's' indicates a string parameter
  $stmt->execute();

  $result = $stmt->get_result();

  if ($result->num_rows > 0) {
    // 2. Generate a unique, secure reset token.
    $reset_token = bin2hex(random_bytes(32)); // Use bin2hex for a more secure random token

    // 3.  Store the token in the database, associated with the user's email
    $query = "INSERT INTO password_resets (user_id, token, expiry_time) VALUES (?, ?, ?)";
    $stmt = $db->prepare($query);
    $stmt->bind_param("iii", $result->fetch_assoc()['id'], $reset_token, $expiry_time);
    $stmt->execute();

    // Success!  Return a message to the user.
    return [
      "success" => true,
      "message" => "Password reset email sent.  Check your inbox!"
    ];

  } else {
    // Email not found
    return [
      "success" => false,
      "message" => "Email address not found."
    ];
  }

  //  Important:  Always close the statements
  $stmt->close();
}


// --- Example Usage (Illustrative - Replace with your actual logic) ---

// Assuming $db is your database connection object

// $email = "user@example.com";  // The user's email address
// $reset_token = $_POST['reset_token']; // Get the token from the form
// $expiry_time = 3600; // 1 hour

// $result = forgot_password($email, $reset_token, $expiry_time);

// if ($result['success']) {
//   echo "<p>" . $result['message'] . "</p>";
// } else {
//   echo "<p>Error: " . $result['message'] . "</p>";
// }

?>
