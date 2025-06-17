

<?php

// Assuming you have a database connection established (e.g., $db)
//  and a 'users' table with 'email' and 'password' columns.
//  This is a simplified example; in a real-world scenario, you'd
//  add more security measures.

function forgot_password($email, $db) {
  // 1. Validate Email
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    return "Invalid email address.";
  }

  // 2. Check if user exists
  $result = $db->query("SELECT id, password FROM users WHERE email = '$email'");

  if ($result->num_rows === 0) {
    return "User not found.";
  }

  $user = $result->fetch_assoc();
  $userId = $user['id'];
  $hashedPassword = $user['password']; //This should be hashed in your database!

  // 3. Generate a temporary password
  $tempPassword = generate_temp_password();

  // 4. Update the database with the temporary password
  $db->query("UPDATE users SET password = '$tempPassword' WHERE id = '$userId'");

  // 5.  Store the temporary password in a temporary table or session 
  //     (This is crucial - don't just log it in the user's database).
  //     Example using a temporary table:
  $db->query("INSERT INTO password_resets (user_id, reset_token, expires_at)
            VALUES ($userId, '$tempPassword', NOW() + INTERVAL 1 HOUR)");


  // 6. Return a reset link (email the user with this link)
  return "<a href='reset_password.php?token=$tempPassword'>Click here to reset your password</a>";

  // Or, you can return the token directly if you are handling the reset in the same page.
  //return $tempPassword;
}

//Helper function to generate a temporary password
function generate_temp_password() {
  $length = 12; // Adjust as needed
  $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
  $password = '';
  $character_length = strlen($characters);
  for ($i = 0; $i < $length; $i++) {
    $random_number = rand(0, $character_length - 1);
    $password .= substr($characters, $random_number);
  }
  return $password;
}



//Example Usage (Illustrative - needs actual database setup)
// $email = "test@example.com";
// $resetLink = forgot_password($email);
// echo $resetLink;

?>
