    <label for="email">Email Address:</label>
    <input type="email" id="email" name="email" placeholder="your.email@example.com" required>
    <button type="submit">Reset Password</button>
</form>

</body>
</html>


<?php

// Assuming you have a database connection established (e.g., $db)
// and user information stored in a table (e.g., 'users') with columns like 'email' and 'password_hash'

function forgotPassword($email, $db) {
  // 1. Check if the email exists in the database
  $stmt = $db->prepare("SELECT id, password_hash FROM users WHERE email = ?");
  $stmt->bind_param("s", $email); // "s" indicates a string parameter
  $stmt->execute();

  $result = $stmt->get_result();

  if ($result->num_rows == 0) {
    // Email not found
    $stmt->close();
    return false;
  }

  $user = $result->fetch_assoc();
  $stmt->close();


  // 2. Generate a unique token
  $token = bin2hex(random_bytes(32)); // Generate a cryptographically secure random token

  // 3. Store the token and user ID in a temporary table
  //  This prevents the token from being reused and helps track password reset attempts
  $stmt = $db->prepare("INSERT INTO password_resets (user_id, token, expires_at) VALUES (?, ?, ?)");
  $stmt->bind_param("iii", $user['id'], $token, time() + 3600); // 'i' indicates integer, time() + 3600 sets expiration to 1 hour
  $stmt->execute();
  $resetId = $db->insert_id; // Get the ID of the inserted row
  $stmt->close();


  // 4. Send the password reset link to the user's email
  $resetLink = "https://yourwebsite.com/reset-password?token=$token"; // Replace with your website's URL
  //  You'll need to implement email sending here.  For example:
  //  mail($user['email'], "Reset Your Password", "Click this link to reset your password: $resetLink");


  // 5. Return success or failure
  return true;  // Indicates the link was successfully generated and sent (implementation of email sending is external)
}



// Example Usage (assuming you have a form to submit the email)
//  This is just a demonstration. Replace with your actual form handling.
//  For example, in a form submission:

//   $email = $_POST['email'];
//   if (isset($email) && !empty($email)) {
//     if (forgotPassword($email, $db)) {
//       echo "A password reset link has been sent to your email address.";
//     } else {
//       echo "Email not found or an error occurred while generating the reset link.";
//     }
//   } else {
//     echo "Please enter your email address.";
//   }


?>
