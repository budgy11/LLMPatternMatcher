

<?php

// Assuming you have a database connection established
// (e.g., $db_connection = mysqli_connect("localhost", "username", "password", "database_name");)

/**
 * Forgets a user's password.
 *
 * @param string $email The user's email address.
 * @return bool True if a password reset email was sent, false otherwise.
 */
function forgot_password(string $email): bool
{
    // Sanitize and validate the email address
    $email = filter_var($email, FILTER_SANITIZE_EMAIL);
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        error_log("Invalid email format provided: " . $email); // Log for debugging
        return false;
    }

    // Check if the user exists
    $user = getUserByEmail($email);

    if ($user === false) {
        error_log("User with email " . $email . " not found."); // Log for debugging
        return false;
    }

    // Generate a unique token and expiration time
    $token = generate_unique_token();
    $expiration_time = time() + (2 * 60 * 60); // Token expires in 2 hours

    // Store the token and expiration time in the database
    $result = store_token($user['id'], $token, $expiration_time);

    if (!$result) {
        error_log("Failed to store token for user " . $email); // Log for debugging
        return false;
    }

    // Send the password reset email
    if (!send_password_reset_email($user['email'], $token, WEBSITE_URL)) {
        error_log("Failed to send password reset email to " . $email); // Log for debugging
        // Optionally, you might try to delete the token in the database if sending failed
        delete_token($user['id'], $token);
        return false;
    }

    return true;
}


/**
 * Helper function to get user by email.  Replace with your actual database query.
 *
 * @param string $email The email address to search for.
 * @return mysqli_result|false Returns the result of the query or false if no user is found.
 */
function getUserByEmail(string $email): mixed
{
    // Replace this with your actual database query. This is a placeholder.
    // Example using mysqli:
    // $sql = "SELECT * FROM users WHERE email = ?";
    // $stmt = $db_connection->prepare($sql);
    // $stmt->bind_param("s", $email);
    // $stmt->execute();
    // $result = $stmt->get_result();
    //
    // If a row is found:
    // if ($row = $result->fetch_assoc()) {
    //     return $row;
    // } else {
    //   return false;
    // }

    // Dummy data for demonstration
    $dummy_users = [
        ['id' => 1, 'email' => 'test@example.com', 'password' => 'password123'],
        ['id' => 2, 'email' => 'another@example.com', 'password' => 'secure_password']
    ];

    foreach ($dummy_users as $user) {
        if ($user['email'] == $email) {
            return $user;
        }
    }
    return false;
}

/**
 * Helper function to generate a unique token.
 *
 * @return string A unique token.
 */
function generate_unique_token(): string
{
    return bin2hex(random_bytes(32));
}

/**
 * Helper function to store the token and expiration time in the database.
 * Replace with your actual database query.
 *
 * @param int $user_id The ID of the user.
 * @param string $token The token.
 * @param int $expiration_time The expiration time (timestamp).
 * @return bool True if the token was stored successfully, false otherwise.
 */
function store_token(int $user_id, string $token, int $expiration_time): bool
{
    // Replace with your actual database query.  This is a placeholder.
    // Example using mysqli:
    // $sql = "INSERT INTO password_tokens (user_id, token, expiration_time) VALUES (?, ?, ?)";
    // $stmt = $db_connection->prepare($sql);
    // $stmt->bind_param("sss", $user_id, $token, $expiration_time);
    // $result = $stmt->execute();
    // return $result;

    // Dummy data for demonstration.
    $dummy_tokens = [
        ['user_id' => 1, 'token' => 'token123', 'expiration_time' => time() + (2 * 60 * 60)],
        ['user_id' => 2, 'token' => 'token456', 'expiration_time' => time() + (2 * 60 * 60)]
    ];
    
    // Check if token already exists. If so, update it.
    $existing_token =  array_filter($dummy_tokens, function ($key, $value) use ($user_id, $token) {
        return $key == $user_id;
    }, ARRAY_FILTER_USE_KEY);
    
    if (!empty($existing_token)) {
        $dummy_tokens[$existing_token[key($existing_token)]]['token'] = $token;
        $dummy_tokens[$existing_token[key($existing_token)]]['expiration_time'] = $expiration_time;
        
        return true;
    }

    return false;
}


/**
 * Helper function to delete the token from the database.  (Optional)
 * Replace with your actual database query.
 *
 * @param int $user_id The ID of the user.
 * @param string $token The token.
 * @return bool True if the token was deleted successfully, false otherwise.
 */
function delete_token(int $user_id, string $token): bool {
    // Replace with your actual database query. This is a placeholder.
    // Example using mysqli:
    // $sql = "DELETE FROM password_tokens WHERE user_id = ? AND token = ?";
    // $stmt = $db_connection->prepare($sql);
    // $stmt->bind_param("ss", $user_id, $token);
    // $result = $stmt->execute();
    // return $result;

     // Dummy data for demonstration.
     $dummy_tokens = [
         ['user_id' => 1, 'token' => 'token123', 'expiration_time' => time() + (2 * 60 * 60)],
         ['user_id' => 2, 'token' => 'token456', 'expiration_time' => time() + (2 * 60 * 60)]
     ];
     
     foreach ($dummy_tokens as $key => $value) {
        if ($value['token'] == $token && $value['user_id'] == $user_id) {
            unset($dummy_tokens[$key]);
            return true;
        }
     }
     return false;
}



/**
 * Helper function to send the password reset email.
 * Replace with your actual email sending logic.
 *
 * @param string $email The email address.
 * @param string $token The token.
 * @param string $website_url The URL of your website.
 * @return bool True if the email was sent successfully, false otherwise.
 */
function send_password_reset_email(string $email, string $token, string $website_url): bool
{
    // Replace with your actual email sending logic.
    // Example:
    // $subject = "Password Reset";
    // $body = "Click this link to reset your password: " . $website_url . "?token=" . $token;
    // $headers = "From: " . WEBSITE_EMAIL;
    // mail($email, $subject, $body, $headers);
    // return true;

    // Dummy email sending - just returns true for demo purposes
    echo "Sending password reset email to " . $email . " with token " . $token . ".  Website URL: " . $website_url;
    return true;
}
?>
