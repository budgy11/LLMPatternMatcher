  <label for="email">Email Address:</label>
  <input type="email" id="email" name="email" placeholder="Your Email" required>
  <button type="submit">Reset Password</button>
</form>


<?php

// Assuming you have a database connection established (e.g., $db connection)

/**
 * Resets a user's password.
 *
 * @param string $email The user's email address.
 * @return bool True if password reset initiated successfully, false otherwise.
 */
function forgot_password(string $email): bool
{
  // 1. Validate Email
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    error_log("Invalid email format provided: " . $email);  // Log the invalid email for debugging
    return false;
  }

  // 2. Check if User Exists
  $user = get_user_by_email($email); // Implement this function (see example below)

  if ($user === null) {
    error_log("User with email " . $email . " not found."); // Log the user not found
    return false;
  }


  // 3. Generate a Unique Token (Important for security!)
  $token = generate_unique_token(); // Implement this function (see example below)


  // 4. Store Token and User ID in Database (Temporary)
  //    This is a temporary link - don't store sensitive information directly.
  $result = store_token_for_user($user['id'], $token);

  if (!$result) {
      error_log("Failed to store token for user " . $email);
      return false;
  }


  // 5. Send Password Reset Email
  $subject = "Password Reset Request";
  $message = "Click this link to reset your password: " .  $_SERVER['REQUEST_SCHEME'] . "://" . $_SERVER['HTTP_HOST'] . "/reset_password?token=" . $token;
  $headers = "From: your_website@example.com\r
";
  $sent = send_email($email, $subject, $message, $headers);

  if (!$sent) {
      error_log("Failed to send password reset email to " . $email);
      // Optionally, you could delete the token from the database if email sending fails.
      // delete_token_for_user($user['id']);
      return false;
  }


  return true;
}



/**
 * Helper function to get user by email (Placeholder - Implement your database logic here)
 *
 * @param string $email The email address to search for.
 * @return array|null An array containing user data if found, or null if not.
 */
function get_user_by_email(string $email): ?array {
    // **Replace this with your actual database query**
    // Example:
    // $query = "SELECT * FROM users WHERE email = '$email'";
    // $result = mysqli_query($db, $query); // Or use PDO or your database driver
    // if (mysqli_num_rows($result) > 0) {
    //   $user = mysqli_fetch_assoc($result);
    //   return $user;
    // } else {
    //   return null;
    // }

  // Placeholder for demonstration:
  $users = [
    ['id' => 1, 'email' => 'test@example.com']
  ];
  foreach($users as $user){
    if($user['email'] == $email){
      return $user;
    }
  }
  return null;
}



/**
 * Helper function to generate a unique token.
 * This should be a cryptographically secure random string.
 *
 * @return string A unique token.
 */
function generate_unique_token(): string {
    return bin2hex(random_bytes(32)); // More secure than rand()
}

/**
 * Helper function to store the token for a user (implementation depends on your database)
 *
 * @param int $userId The ID of the user.
 * @param string $token The token to store.
 * @return bool True if token stored successfully, false otherwise.
 */
function store_token_for_user(int $userId, string $token): bool {
    // **Replace this with your actual database logic**
    // Example using mysqli:
    // $query = "INSERT INTO password_tokens (user_id, token, expiry_date) VALUES ($userId, '$token', NOW() + INTERVAL 30 DAY)";
    // if (mysqli_query($db, $query) === false) {
    //   return false;
    // }
    return true;
}



/**
 * Helper function to delete the token from the database.
 * (Optional - For added security.  Consider if the token is short-lived)
 * @param int $userId
 * @return bool
 */
function delete_token_for_user(int $userId): bool {
    // Implement your database logic to delete the token.
    // Example:
    // $query = "DELETE FROM password_tokens WHERE user_id = $userId";
    // if (mysqli_query($db, $query) === false) {
    //   return false;
    // }
    return true;
}



/**
 * Placeholder function to send an email.
 * You'll need to integrate with your email sending service here.
 * @param string $to
 * @param string $subject
 * @param string $message
 * @param string $headers
 * @return bool
 */
function send_email(string $to, string $subject, string $message, string $headers): bool {
    // **Replace this with your actual email sending logic**
    // Example using a basic SMTP implementation (not recommended for production):
    // $smtp = new PHPMailer();
    // $smtp->SMTPDebugEnable = false;
    // $smtp->Host = 'smtp.example.com';
    // $smtp->Port = 587;
    // $smtp->Username = 'your_username';
    // $smtp->Password = 'your_password';
    // $smtp->SetFrom('your_website@example.com', 'Your Website');
    // $smtp->AddAddress($to);
    // $smtp->Subject = $subject;
    // $smtp->MsgBody = $message, 'html';
    // $smtp->IsHTML(true);
    // $smtp->Send();

    return true; // Placeholder - always returns true
}


<?php

// Database connection details (Replace with your actual credentials)
$db_host = 'localhost';
$db_name = 'your_database_name';
$db_user = 'your_database_username';
$db_password = 'your_database_password';

//  ---  Function to reset password  ---
function forgot_password($email, $new_password, $db) {
    global $db_host, $db_name, $db_user, $db_password;

    // 1. Verify Email Exists
    $stmt = $db->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        return "Email not found.";
    }

    $user_id = $result->fetch_assoc()['id'];

    // 2.  Hash the New Password
    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);


    // 3. Update Password in Database
    $stmt = $db->prepare("UPDATE users SET password = ? WHERE id = ?");
    $stmt->bind_param("ss", $hashed_password, $user_id);

    if ($stmt->execute() === false) {
        return "Failed to update password. Database error: " . $stmt->error;
    }
    
    $stmt->close();

    return "Password reset email sent.";

}


// Example Usage (Illustrative - Replace with your actual form handling)
// This is just for demonstration; you should implement proper form handling
// for security (CSRF protection, input validation, etc.)

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $new_password = $_POST["new_password"];

    //  Database Connection (Establish Connection - crucial for any database operations)
    global $db_host, $db_name, $db_user, $db_password;

    $db = new mysqli($db_host, $db_user, $db_password, $db_name);

    if ($db->connect_error) {
        die("Connection failed: " . $db->connect_error);
    }


    $result = forgot_password($email, $new_password, $db);
    echo $result; // Output:  "Password reset email sent." or an error message
    $db->close();
}

?>
