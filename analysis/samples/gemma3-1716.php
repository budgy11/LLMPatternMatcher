        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>
        <button type="submit">Reset Password</button>
    </form>

</body>
</html>


<?php

// Assuming you have a database connection established and a user table
// with an 'email' field.  This is a simplified example.
// In a real application, you'd use prepared statements to prevent SQL injection.

function forgot_password($email, $token) {
  // 1. Validate Email and Token
  if (empty($email) || empty($token)) {
    return "Invalid email or token.";
  }

  // 2. Check if the user exists and has a valid password reset token
  //    (Consider database query here, e.g., using mysqli or PDO)

  // Dummy user data for demonstration purposes
  $users = [
    ['id' => 1, 'email' => 'test@example.com', 'password' => 'hashed_password'],
    ['id' => 2, 'email' => 'another@example.com', 'password' => 'another_hashed_password']
  ];

  $user = null;
  foreach ($users as $u) {
    if ($u['email'] === $email) {
      $user = $u;
      break;
    }
  }

  if ($user === null) {
    return "User not found.";
  }

  // 3. Verify the token (Important security step - replace with your token validation logic)
  // In a real application, you'd compare the token against a hashed value
  // stored in the database, NOT against a plain text token.
  if ($token !== 'YOUR_SECRET_TOKEN') { //  Replace with your actual token
    return "Invalid token.";
  }

  // 4.  Reset the password (In a real app, this would also hash the new password)
  //    For this example, we'll just return a message indicating the password has been reset.
  return "Password reset link sent to $email";

  // In a real application, you'd update the database to store a 'reset_token'
  // and an expiration timestamp.  You'd also hash the new password.
}



// Example Usage (to demonstrate the function)
//  Important:  Don't use this example directly in a production environment!

// This simulates a user requesting a password reset.
$email = 'test@example.com';
$token = 'YOUR_SECRET_TOKEN';  // Replace with a generated or secure token
$reset_message = forgot_password($email, $token);

echo $reset_message . "<br>";

?>
