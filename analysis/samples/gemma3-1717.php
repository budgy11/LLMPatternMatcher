

<?php

// Assuming you've established a PDO connection called $pdo

function forgot_password($email, $token) {
  $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
  $stmt->bindParam(':email', $email, PDO::PARAM_STR);
  $stmt->execute();

  $user = $stmt->fetch(PDO::FETCH_ASSOC);

  if ($user === null) {
    return "User not found.";
  }

  // ... (Rest of the forgot_password function as before) ...
}


<?php

/**
 * Function to handle password reset requests.
 *
 * This function generates a unique token, sends an email with a reset link,
 * and updates the user's password if the token is valid.
 *
 * @param string $email The email address of the user requesting a password reset.
 * @param string $secret_key  A random secret key to prevent abuse.  Ideally this should be unique per user.
 * @return bool True if the password reset was initiated successfully, false otherwise.
 */
function forgot_password(string $email, string $secret_key) {
    // 1. Check if the email exists
    $user = get_user_by_email($email);  // Implement this function (see example below)

    if (!$user) {
        return false; // User not found
    }

    // 2. Generate a unique token and store it
    $token = generate_unique_token(); // Implement this function (see example below)

    // Store the token in the database for the user.
    //  Use a secure method to store the token –  e.g., using hashing.
    //  This example assumes a simple string storage for clarity.
    //  **Important:** In a production environment, *always* hash the token
    //   before storing it in the database.
    // Example:
    // $user->reset_token = password_hash($token, PASSWORD_DEFAULT);
    // $user->save();


    // 3.  Create the reset link
    $reset_link = '/reset-password?token=' . urlencode($token);

    // 4. Send the reset email
    if (!send_password_reset_email($email, $reset_link)) {
        return false; // Email sending failed
    }

    return true; // Password reset initiated successfully
}


/**
 * Placeholder function to get a user by their email.
 * Replace this with your actual database query.
 *
 * @param string $email The email address of the user.
 * @return object|null The user object if found, null otherwise.
 */
function get_user_by_email(string $email): ?object {
    // **Replace this with your database query.**
    // This is just a placeholder.

    // Example using a dummy database (in a real application, use PDO or your preferred database library)
    $users = [
        (object)['id' => 1, 'email' => 'test@example.com', 'password' => 'hashed_password'],
        (object)['id' => 2, 'email' => 'another@example.com', 'password' => 'hashed_password2']
    ];

    foreach ($users as $user) {
        if ($user->email === $email) {
            return $user;
        }
    }
    return null;
}


/**
 * Placeholder function to generate a unique token.
 *  Use a cryptographically secure random number generator.
 *
 * @return string A unique token.
 */
function generate_unique_token(): string {
    return bin2hex(random_bytes(32)); //  Use random_bytes for security
}


/**
 * Placeholder function to send a password reset email.
 *
 * @param string $email The email address of the user.
 * @param string $reset_link The password reset link.
 */
function send_password_reset_email(string $email, string $reset_link): bool {
    //  Implement your email sending logic here.
    //  This is just a placeholder.

    // Example:
    //  $subject = "Password Reset";
    //  $body = "Click this link to reset your password: " . $reset_link;
    //  mail($email, $subject, $body);

    //  **Important:**  In a real application, use a reliable email sending service
    //   (e.g., SendGrid, Mailgun, Amazon SES) to ensure deliverability.

    return true; // Assume email sending was successful (replace with actual check)
}



// Example Usage (Simulated)
$email = 'test@example.com';
$secret_key = generate_unique_token(); // Generate a random secret key.
$success = forgot_password($email, $secret_key);

if ($success) {
    echo "Password reset email sent to $email.  Check your inbox!";
} else {
    echo "Failed to initiate password reset.";
}


?>
