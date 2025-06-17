  <label for="email">Email Address:</label>
  <input type="email" id="email" name="email" required>
  <button type="submit">Request Password Reset</button>
</form>


<?php

// This is a simplified example and should be used with caution.
// It's crucial to implement proper security measures in a real application.

// Assume you have a database connection established (e.g., $db)
// and user credentials stored in a database table (e.g., 'users')
// with columns like 'id', 'email', 'password_hash', 'salt'

function forgot_password($email, $db) {
  // 1. Validate Email
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    return "Invalid email address.";
  }

  // 2. Check if the user exists
  $user = $db->query("SELECT id, password_hash, salt FROM users WHERE email = '$email' LIMIT 1");

  if ($user->num_rows === 0) {
    return "User not found.";
  }

  $user_data = $user->fetch_assoc();
  $id = $user_data['id'];
  $password_hash = $user_data['password_hash'];
  $salt = $user_data['salt'];

  // 3. Generate a unique token
  $token = bin2hex(random_bytes(32)); //  Generate a cryptographically secure random token

  // 4.  Store the token in the database, associated with the user
  $db->query("INSERT INTO password_resets (user_id, token, expires_at) VALUES ($id, '$token', NOW() + INTERVAL 24 HOUR)"); // Store for 24 hours

  // 5.  Send an email to the user with the token
  $email_subject = "Password Reset";
  $email_body = "To reset your password, please click on the following link: <a href='" . $_SERVER['PHP_SELF'] . "?reset=$token'</a>"; //  '$_SERVER['PHP_SELF']' ensures correct link generation.
  $email_to = $email;
  $headers = "From: your_website@example.com";

  mail($email_to, $email_body, $headers);

  return "Password reset email sent to $email.";
}



//  Example usage (This part is for testing purposes - NOT for actual user interaction)
//  You'd replace this with your actual user input and database interactions.
//  For demonstration, we're using a hardcoded email.

// Simulate a database connection (replace with your actual database connection)
$db = new mysqli("localhost", "your_username", "your_password", "your_database");
if ($db->connect_error) {
  die("Connection failed: " . $db->connect_error);
}


// Test the function
$result = forgot_password("testuser@example.com", $db);
echo $result;


// Clean up the database connection
$db->close();
?>
