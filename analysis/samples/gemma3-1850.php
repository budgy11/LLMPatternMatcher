        <button type="submit">Reset Password</button>
    </form>

</body>
</html>


<?php

// Assuming you have a database connection established and named $db

/**
 * Resets a user's password.
 *
 * @param string $email The email address of the user requesting password reset.
 * @return string A message indicating success or failure.
 */
function forgot_password(string $email) {
    // 1. Validate Input (Important for Security)
    if (empty($email)) {
        return "Error: Email address is required.";
    }

    // Sanitize the email address to prevent potential vulnerabilities
    $email = filter_var($email, FILTER_SANITIZE_EMAIL);
    if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
        return "Error: Invalid email address format.";
    }

    // 2. Check if User Exists
    $user = getUserByEmail($email); // Assuming you have a function to retrieve user by email
    if ($user === null) {
        return "Error: User not found.";
    }

    // 3. Generate a Unique Token and Store it
    $token = generate_unique_token();  // Assuming you have a function to generate a unique token
    $expiry_time = time() + (3600 * 24); // Token expires in 24 hours (adjust as needed)

    // Store the token and expiry time in the database, linked to the user.
    // This is a crucial step.  The example below is illustrative;
    // your actual implementation will vary based on your database schema.
    store_token($user->id, $token, $expiry_time);

    // 4. Send the Password Reset Email
    $subject = "Password Reset Request";
    $headers = "From: your_email@example.com" . "\r
"; // Replace with your email
    $message = "Click on this link to reset your password: " . base_url . "reset_password?token=" . urlencode($token);
    $result = send_email($email, $subject, $headers, $message);

    if ($result) {
        return "Password reset link has been sent to your email.";
    } else {
        return "Error: Failed to send email.";
    }
}


/**
 * Placeholder functions (You'll need to implement these based on your application)
 */
// Example: Get user by email (Replace with your actual database query)
function getUserByEmail(string $email): ?User {
    // Example database query - Replace with your actual query
    // Assuming you have a User class/model
    // This is just a placeholder - replace with your logic
    //  $db = get_database_connection();  // Get database connection
    //  $query = "SELECT * FROM users WHERE email = ?";
    //  $stmt = $db->prepare($query);
    //  $stmt->execute([$email]);
    //  $user = $stmt->fetch(PDO::FETCH_ASSOC);
    //  if ($user) {
    //      return new User($user); // Create a User object
    //  }
    //  return null;

    // Placeholder for demo - returns a dummy user object
    return new User(['id' => 1, 'email' => 'test@example.com']);
}

// Example: Generate a unique token (You'll need to implement this)
function generate_unique_token(): string {
    return bin2hex(random_bytes(32)); // A secure random string
}

// Example: Store the token and expiry time in the database (Implement this)
function store_token(int $userId, string $token, int $expiry_time): void {
  // Implement the logic to store the token and expiry time in your database
  //  e.g., using a database query to update the user's record.
  // Example:
  // $db = get_database_connection();
  // $query = "UPDATE users SET token = ?, expiry_time = ? WHERE id = ?";
  // $stmt = $db->prepare($query);
  // $stmt->execute([$token, $expiry_time, $userId]);
}

// Example: Send an email (You'll need to implement this, likely using a library)
function send_email(string $to, string $subject, string $headers, string $message): bool {
    //  Implement the email sending logic using a library like PHPMailer
    //  This is a placeholder -  replace with your actual email sending code.
    //  For demonstration, simply return true.
    //  e.g.,
    //  $mailer = new PHPMailer(true);
    //  $mailer->addAddress($to, $to);
    //  $mailer->setFrom('your_email@example.com', 'Your Name');
    //  $mailer->Subject = $subject;
    //  $mailer->Body = $message;
    //  return $mailer->send();

    // Placeholder
    return true;
}



// Example User Class (Customize as needed)
class User {
    public int $id;
    public string $email;

    public function __construct(array $data) {
        $this->id = $data['id'];
        $this->email = $data['email'];
    }
}



// Example Usage:
$email = "test@example.com"; // Replace with a user's email
$result = forgot_password($email);
echo $result . "
";

?>
