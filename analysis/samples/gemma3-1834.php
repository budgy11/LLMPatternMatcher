

<?php

/**
 *  Forgot Password Function
 *
 *  This function sends an email with a password reset link to the user's registered email address.
 *
 *  @param string $email The email address of the user.
 *  @return bool True if the email was sent successfully, false otherwise.
 */
function forgot_password(string $email): bool
{
    // 1. Validate Email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        error_log("Invalid email address provided: " . $email); // Log invalid email
        return false;
    }

    // 2. Check if the user exists
    $user = get_user_by_email($email);  // Assumes you have this function
    if (!$user) {
        error_log("User with email " . $email . " not found.");
        return false;
    }

    // 3. Generate a unique token and expiration time
    $token = generate_unique_token();
    $expiration_time = time() + (2 * 60 * 60); // Token valid for 2 hours. Adjust as needed.
    
    // 4. Store token and expiration time in the database
    if (!store_token_in_database($user->id, $token, $expiration_time)) {
      error_log("Failed to store token in database for user " . $email);
      return false;
    }
    
    // 5. Build the reset link
    $reset_link = app_url() . "/reset_password?token=" . urlencode($token); // Use app_url() or your base URL

    // 6. Send the reset email
    if (!send_reset_password_email($email, $reset_link)) {
        error_log("Failed to send reset password email to " . $email);
        // You might want to handle this failure differently (e.g., display an error message)
        return false;
    }

    return true;
}


/**
 * Placeholder functions - Replace with your actual implementations.
 */
function get_user_by_email(string $email): ?User // Assuming User is your user model
{
    // Replace with your database query to retrieve the user by email
    // Example:
    // $result = mysqli_query($conn, "SELECT * FROM users WHERE email = '$email'");
    // if (mysqli_num_rows($result) > 0) {
    //    $user = new User();
    //    $user->load($result->fetch_assoc());
    //    return $user;
    // }
    // return null;

    // Dummy User class
    class User {
        public $id;
        public $email;
        public function load($data) {
            $this->id = $data['id'];
            $this->email = $data['email'];
        }
    }

    return null;
}

function generate_unique_token(): string
{
    return bin2hex(random_bytes(32)); // Generate a cryptographically secure random token
}

function store_token_in_database(int $user_id, string $token, int $expiration_time): bool
{
  // Replace with your database query to store the token and expiration time
  // Example:
  // $result = mysqli_query($conn, "INSERT INTO password_tokens (user_id, token, expiration_time) VALUES ($user_id, '$token', $expiration_time)");
  // return mysqli_affected_rows($conn) > 0;

  return true; //Dummy implementation
}

function urlencode($string) {
  // Use built-in urlencode function for portability
  return urlencode($string);
}

function send_reset_password_email(string $email, string $reset_link): bool
{
    // Replace with your email sending logic
    // Example:
    // $subject = "Password Reset";
    // $body = "Click the following link to reset your password: " . $reset_link;
    // $headers = "From: your_email@example.com\r
";
    // return mail($email, $subject, $body, $headers);
    return true; //Dummy implementation
}

function app_url() {
    // Return your application's base URL
    return "http://localhost/your_app/";
}
?>
