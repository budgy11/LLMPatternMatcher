

<?php

// Assuming you have a database connection established (e.g., $db)

// Function to handle password reset requests
function forgot_password(string $email): bool
{
  // 1. Validate Email
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    error_log("Invalid email format provided."); // Log the error for debugging
    return false;
  }

  // 2. Check if User Exists
  $user = db_get_user_by_email($email);  // Replace with your actual database query
  if (!$user) {
    error_log("User with email $email not found.");
    return false;
  }

  // 3. Generate a Unique Token
  $token = generate_unique_token();

  // 4. Store Token in Database (Associate with User)
  store_token_for_user($user->id, $token);  // Replace with your actual database logic

  // 5. Send Password Reset Email
  $subject = "Password Reset Request";
  $message = "Please click the following link to reset your password:
" .
             "<a href='" .  base_url() . "/reset_password?token=$token'>" . base_url() . "/reset_password</a>"; // Replace base_url()
  $headers = "From: " . get_site_email_address() . "\r
";
  mail($email, $message, $headers);

  return true;
}

// --------------------- Helper Functions (Implement these) ---------------------

// Placeholder for generating a unique token (e.g., using UUID)
function generate_unique_token(): string
{
    return bin2hex(random_bytes(32)); // A simple, but not cryptographically secure, example.
}

// Placeholder for getting user by email (replace with your DB query)
function db_get_user_by_email(string $email): ?User // Use ?User to return null if no user is found
{
  // Replace this with your actual database query to get user by email
  // Example:
  // $sql = "SELECT * FROM users WHERE email = '$email'";
  // $result = mysqli_query($db, $sql);
  // if (mysqli_num_rows($result) > 0) {
  //   $user = mysqli_fetch_assoc($result);
  //   return new User($user); // Assuming you have a User class
  // }
  // return null;
  return null;
}

// Placeholder for storing token in database (replace with your DB logic)
function store_token_for_user(int $userId, string $token): void
{
  // Replace this with your actual database logic to store the token
  //  Example:
  //  $sql = "INSERT INTO password_tokens (user_id, token, expiry_date) VALUES ($userId, '$token', NOW() + INTERVAL 24 HOUR)";
  //  mysqli_query($db, $sql);
}

// Placeholder for getting site email address
function get_site_email_address(): string
{
  // Return your site's email address.
  return 'your_site_email@example.com';
}

// Placeholder for base_url()
function base_url(): string {
  // Return your base URL.
  return 'http://localhost/your_project/';
}



// --------------------- Example Usage (For Testing - Remove in Production) ---------------------

// If you were to call this from a form:

// $email = $_POST['email']; // Get email from the form
// if (isset($email)) {
//     if (forgot_password($email)) {
//       echo "Password reset email sent to $email.";
//     } else {
//       echo "Failed to send password reset email.";
//     }
// } else {
//   echo "Email address is required.";
// }


// --------------------- Important Considerations ---------------------

// 1. Security:
//    - **Never** store passwords directly in the database.  Use hashing (bcrypt, argon2, etc.)
//    - Use a strong, cryptographically secure token generation method.
//    - Implement proper rate limiting to prevent brute-force attacks.
//    -  Always validate and sanitize user input.

// 2. Error Handling:
//    - Log errors for debugging purposes (using error_log()).
//    - Handle edge cases (e.g., invalid email format).

// 3. Token Expiry:
//    -  Set an expiry date for the token (e.g., 24 hours).  This is crucial for security.  After the expiry date, the token becomes useless.

// 4. Database Interaction:
//    -  Replace the placeholder database functions (`db_get_user_by_email`, `store_token_for_user`) with your actual database queries and logic.

// 5.  Email Sending:
//    -  Use a reliable email sending service (e.g., SendGrid, Mailgun, AWS SES) instead of relying on PHP's `mail()` function, which can be unreliable.

// 6. User Class:
//    - Create a `User` class to represent your user data (this is just an example).

?>
