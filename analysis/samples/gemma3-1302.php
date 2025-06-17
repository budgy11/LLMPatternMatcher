    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required>
    <button type="submit">Request Password Reset</button>
</form>


<?php

/**
 * Resets a user's password.
 *
 * This function generates a unique token and sends an email to the user
 * with a link to reset their password.
 *
 * @param string $email The email address of the user.
 * @param string $token A unique token generated for this password reset request.
 * @param $db mysqli Database connection object.
 * @return bool True if the email was sent successfully, false otherwise.
 */
function forgot_password(string $email, string $token, mysqli $db) {
  // 1. Check if the email exists in the database
  $query = "SELECT id, username FROM users WHERE email = ?";
  $stmt = $db->prepare($query);
  $stmt->bind_param("s", $email); // 's' indicates a string parameter
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows == 0) {
    $stmt->close();
    return false; // Email not found
  }

  $user_id = $result->fetch_assoc()['id'];
  $username = $result->fetch_assoc()['username'];

  // 2. Create a password reset token and store it in the database
  $hashed_token = password_hash($token, PASSWORD_DEFAULT); // Securely hash the token

  $query = "INSERT INTO password_resets (user_id, token, expires_at) VALUES (?, ?, NOW())";
  $stmt = $db->prepare($query);
  $stmt->bind_param("ss", $user_id, $hashed_token);
  $stmt->execute();
  $stmt->close();


  // 3. Send the password reset email
  $reset_link = "http://yourdomain.com/reset_password?token=" . urlencode($token); // Replace yourdomain.com
  $to = $email;
  $subject = "Password Reset Request";
  $message = "Click on the following link to reset your password: " . $reset_link;
  $headers = "From: Your Website <admin@yourdomain.com>"; // Replace with your email

  if (mail($to, $subject, $message, $headers)) {
    return true; // Email sent successfully
  } else {
    // If mail fails, delete the token from the database to prevent abuse
    delete_password_reset_token($user_id, $token, $db);
    return false;
  }
}



/**
 * Deletes a password reset token from the database.
 *
 * @param int $user_id The ID of the user.
 * @param string $token The token to delete.
 * @param mysqli $db Database connection object.
 */
function delete_password_reset_token(int $user_id, string $token, mysqli $db) {
  $query = "DELETE FROM password_resets WHERE user_id = ? AND token = ?";
  $stmt = $db->prepare($query);
  $stmt->bind_param("ss", $user_id, $token);
  $stmt->execute();
  $stmt->close();
}



// Example Usage (Illustrative - Replace with your database setup)
// Assuming you have a mysqli connection called $db
/*
$email = "testuser@example.com";
$token = "unique_reset_token_123";

if (forgot_password($email, $token, $db)) {
  echo "Password reset email sent to " . $email;
} else {
  echo "Failed to send password reset email.";
}
*/


// IMPORTANT CONSIDERATIONS & BEST PRACTICES:

// 1. Security:
//    - **Hashing:**  Always use a strong password hashing algorithm like `password_hash()` to store the token securely in the database.  Never store the token in plain text.
//    - **Token Expiry:**  Crucially, set an expiration time for the tokens (e.g., 30 minutes, 1 hour).  Use `NOW()` (MySQL) or a similar function to store the `expires_at` column in the `password_resets` table. This prevents attackers from using the token indefinitely.
//    - **Rate Limiting:** Implement rate limiting to prevent brute-force attacks on the password reset endpoint.
//    - **Input Validation & Sanitization:**  Validate and sanitize all user input to prevent SQL injection and other vulnerabilities.
//    - **HTTPS:** Always use HTTPS to encrypt the communication between the user's browser and your server.

// 2. Database Design:
//    - Create a `password_resets` table in your database.  This table should have at least these columns:
//      - `id` (INT, PRIMARY KEY, AUTO_INCREMENT)
//      - `user_id` (INT, FOREIGN KEY referencing `users.id`)
//      - `token` (VARCHAR, to store the unique token)
//      - `expires_at` (TIMESTAMP, to store the token's expiry time)

// 3.  Error Handling:
//    -  Implement robust error handling.  Log errors for debugging and monitoring.
//    -  Return appropriate error codes or messages to the user.

// 4. User Experience:
//    - Provide clear and informative messages to the user about the password reset process.
//    -  Consider providing a link to the user's account page after the password has been reset.

// 5.  Cleanup: When the user successfully resets their password, it's *essential* to delete the corresponding token from the `password_resets` table. This prevents future attempts to reset the password using the old token.


<?php

// Ensure this file is protected to prevent direct access to the database
if (!defined('PH__PROCESS_GUARD')) {
    die('Access denied');
}


/**
 * Forgets a user's password and sends a password reset link.
 *
 * @param string $email The user's email address.
 * @return bool True if a reset link was sent, false otherwise.
 */
function forgot_password(string $email): bool
{
    // 1. Validate Email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        error_log("Invalid email format provided: " . $email); // Log for debugging
        return false;
    }

    // 2. Check if User Exists
    $user = get_user_by_email($email);
    if (!$user) {
        error_log("User with email " . $email . " not found");
        return false;
    }

    // 3. Generate a Unique Token
    $token = generate_unique_token();

    // 4. Store Token in Database (Hash it for security)
    $hashed_token = hash('sha256', $token); // Use SHA256 for stronger hashing
    $result = save_token_to_database($user->id, $hashed_token);
    if (!$result) {
        error_log("Failed to save token to database for user " . $email);
        return false;
    }

    // 5.  Construct the Password Reset Link
    $reset_link = generate_reset_link($user->email, $token);

    // 6. Send the Reset Email
    if (!send_reset_email($user->email, $reset_link)) {
        error_log("Failed to send password reset email to " . $email);
        // Optional:  You could delete the token from the database here,
        // if you want to ensure the reset link isn't usable if the email
        // fails to send.  However, this increases complexity.
        return false;
    }

    return true;
}


/**
 * Placeholder function to retrieve a user by email.  Implement your database query here.
 * @param string $email
 * @return User|null
 */
function get_user_by_email(string $email): ?User {
    // Replace with your actual database query
    // This is just a dummy example.
    // You'd normally fetch the user from your database table.

    // Example:  Assuming you have a User class
    //  $user =  DB::query("SELECT * FROM users WHERE email = ?", $email)->first();
    //  return $user;

    // Dummy User class for demonstration.
    class User {
        public $id;
        public $email;

        public function __construct(int $id, string $email) {
            $this->id = $id;
            $this->email = $email;
        }
    }

    return new User(1, $email); // Placeholder return
}

/**
 * Placeholder function to generate a unique token.
 * @return string
 */
function generate_unique_token(): string
{
    return bin2hex(random_bytes(32)); // Using bin2hex for better security
}

/**
 * Placeholder function to save the token to the database.
 * @param int $user_id
 * @param string $hashed_token
 * @return bool
 */
function save_token_to_database(int $user_id, string $hashed_token): bool
{
    // Replace with your database insert query
    // Example:
    // DB::table('password_resets')->insert([
    //     'user_id' => $user_id,
    //     'token' => $hashed_token,
    //     'created_at' => time(),
    // ]);
    // Return true if the insert was successful, false otherwise.

    return true; // Placeholder return
}


/**
 * Placeholder function to generate the password reset link.
 * @param string $email
 * @param string $token
 * @return string
 */
function generate_reset_link(string $email, string $token): string
{
    return "/reset_password?email=" . urlencode($email) . "&token=" . urlencode($token);
}


/**
 * Placeholder function to send the password reset email.
 * @param string $email
 * @param string $reset_link
 */
function send_reset_email(string $email, string $reset_link): bool
{
    // Replace with your email sending logic (e.g., using PHPMailer)
    // This is a placeholder - you need to implement this using your preferred email library.

    // Example:
    // $mail = new PHPMailer();
    // $mail->addAddress($email);
    // $mail->Subject = "Password Reset";
    // $mail->Body = "Click this link to reset your password: " . $reset_link;
    // $mail->send();

    // Placeholder:  Simulate sending
    echo "Simulated sending email to: " . $email . " with link: " . $reset_link;
    return true;
}

// Example Usage (Illustrative -  Replace with your actual user data)
$email = "test@example.com";
if (forgot_password($email)) {
    echo "Password reset email sent to " . $email . "
";
} else {
    echo "Failed to send password reset email to " . $email . "
";
}

?>
