

<?php

/**
 * Resets a user's password via email.
 *
 * @param string $email The email address of the user.
 * @param string $newPassword The new password to set.
 * @param string $websiteUrl The URL of your website. (Optional, for link in email)
 * @return bool True on success, false on failure.
 */
function forgotPassword(string $email, string $newPassword, string $websiteUrl = '') {
  // **Security Note:**  This is a simplified example for demonstration.
  // In a production environment, you *MUST* implement robust security measures,
  // including:
  // 1.  Hashing the password before storing it in the database.
  // 2.  Validating email format.
  // 3.  Rate limiting to prevent brute-force attacks.
  // 4.  Using a dedicated email sending service (e.g., SendGrid, Mailgun)
  //     for better deliverability and security.

  // Check if email exists in the database (replace with your actual database logic)
  $user = getUserByEmail($email);

  if (!$user) {
    return false; // User not found
  }

  // Update the user's password in the database (replace with your actual database logic)
  if (!updateUserPassword($user, $newPassword)) {
    return false; // Password update failed
  }


  // Send password reset email
  $subject = 'Password Reset';
  $body = "Please use the following link to reset your password:
" .
          "<a href='" . $websiteUrl . "/reset_password?token=" . generateResetToken($user->id) . "'>Reset Password</a>";
  $headers = "From: " . 'Your Website Name <noreply@yourwebsite.com>' . "\r
";
  // Use mail() for simplicity, but consider a dedicated email sending service.
  if (mail($email, $subject, $body, $headers)) {
    return true;
  } else {
    // Email sending failed - you should log this error.
    return false;
  }
}


/**
 * Placeholder function to retrieve a user by email.  Replace with your database query.
 *
 * @param string $email The email address to search for.
 * @return object|null User object if found, null otherwise.
 */
function getUserByEmail(string $email) {
  // Replace this with your actual database query.
  // Example (assuming you have a 'users' table with an 'email' column)
  // $db = new PDO(/* your database connection details */);
  // $stmt = $db->prepare("SELECT * FROM users WHERE email = ?");
  // $stmt->execute([$email]);
  // $user = $stmt->fetch(PDO::FETCH_OBJ);
  // return $user;

  // Mock user object for demonstration
  $user = new stdClass();
  $user->id = 123; // Example user ID
  return $user;
}


/**
 * Placeholder function to update a user's password in the database.
 * Replace with your actual database query.
 *
 * @param object $user The user object to update.
 * @param string $newPassword The new password to set.
 * @return bool True on success, false on failure.
 */
function updateUserPassword(object $user, string $newPassword) {
  // Replace this with your actual database query.
  // Example:
  // $db = new PDO(/* your database connection details */);
  // $stmt = $db->prepare("UPDATE users SET password = ? WHERE id = ?");
  // $stmt->execute([password_hash($newPassword, PASSWORD_DEFAULT), $user->id]);
  // return true;

  // Mock success for demonstration
  return true;
}

/**
 * Generates a unique reset token.
 *
 * @param int $userId The ID of the user.
 * @return string  A unique token.
 */
function generateResetToken() {
  return bin2hex(random_bytes(32)); // Use a cryptographically secure random number generator
}



// --- Example Usage ---
// You would call this function from your form submission code:

// $email = $_POST['email'];
// $newPassword = $_POST['newPassword'];

// if (isset($email) && isset($newPassword)) {
//   if (forgotPassword($email, $newPassword)) {
//     echo "Password reset email has been sent.  Check your inbox!";
//   } else {
//     echo "Error resetting password. Please try again.";
//   }
// } else {
//   echo "Please enter your email and a new password.";
// }


// --- Note:  This is a VERY simplified example. ---
// In a real application, you would:
// 1.  Validate the email and password input thoroughly.
// 2.  Use a dedicated email sending service for better reliability and security.
// 3.  Implement robust security measures to protect against attacks.
// 4.  Handle errors gracefully.


<?php

// Assuming you have a database connection established (e.g., $db connection)

/**
 * Resets a user's password.
 *
 * @param string $email The user's email address.
 * @return bool True if the password was reset successfully, false otherwise.
 */
function forgotPassword(string $email)
{
    // 1. Validate Email (Important!)
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        error_log("Invalid email format provided."); // Log the error for debugging
        return false;
    }

    // 2. Check if User Exists
    $user = getUserByEmail($email); //  Assumed function to get user by email
    if (!$user) {
        error_log("User with email '$email' not found."); //Log the error
        return false;
    }

    // 3. Generate a Unique Token (for security)
    $token = generateUniqueToken(); //  Assumed function to generate a unique token

    // 4. Store Token and User ID in a Temporary Table
    //    (This is important for security -  don't store tokens directly in the main user table)
    $query = "INSERT INTO password_reset_tokens (user_id, token, expires_at)
              VALUES ($user->id, '$token', NOW() + INTERVAL 24 HOUR)";
    mysqli_query($GLOBALS['db'], $query); // Use mysqli_query or PDO for better security.

    // 5.  Send Password Reset Email (Email Logic - Not Implemented Here)
    //   This is where you would send an email with a link containing the token.
    //   The email link should lead to a page where the user can enter a new password.
    //   Example:
    //   $subject = "Password Reset";
    //   $to = $user->email;
    //   $headers = "From: your-email@example.com";
    //   $link = "/reset-password?token=$token";
    //   mail($to, $subject, $link, $headers);
    //   echo "Password reset email sent to $email. Check your inbox.";


    return true; // Indicate successful token generation and storage.
}


/**
 * Assumed function to get user by email.  Implement this based on your database.
 * @param string $email
 * @return mysqli_result|null
 */
function getUserByEmail(string $email) {
    // Replace with your database query
    // This is just a placeholder.  Adjust to your database and setup.
    // Example using mysqli:
    // $query = "SELECT * FROM users WHERE email = '$email'";
    // $result = mysqli_query($GLOBALS['db'], $query);
    // if (mysqli_num_rows($result) > 0) {
    //   return mysqli_fetch_assoc($result);
    // } else {
    //   return null;
    // }
    //  A placeholder returning a dummy user object
    return new \stdClass(); // Returns a new empty object.
}


/**
 * Assumed function to generate a unique token.
 * @return string
 */
function generateUniqueToken()
{
    return bin2hex(random_bytes(32)); // Generates a 32-byte random hex string
}



// Example Usage (Illustrative - Not part of the forgotPassword function)
// You would typically trigger this through a form submission.
// $email = $_POST['email'];
// if (isset($email)) {
//     if (forgotPassword($email)) {
//         echo "Password reset email sent to $email.";
//     } else {
//         echo "Failed to generate password reset token.";
//     }
// }


?>
