

<?php

// Assuming you have a database connection established
// (e.g., $db is your database connection object)
// and a user table with an 'email' column.

/**
 * Resets a user's password.
 *
 * @param string $email The user's email address.
 * @return bool True on success, false on failure.
 */
function forgotPassword(string $email): bool
{
  // 1. Check if the email exists
  $user = db_query("SELECT id, email FROM users WHERE email = %s", $email);  // Use prepared statement!

  if ($user->num_rows == 0) {
    // Email not found
    return false;
  }

  // 2. Generate a unique token (for security)
  $token = bin2hex(random_bytes(32)); // Generate a strong random token

  // 3. Store the token in the database, associated with the user
  $result = db_query("INSERT INTO password_resets (user_id, token, created_at) 
                       VALUES (%d, %s, NOW())", $user->fetch_assoc()['id'], $token);

  if (!$result) {
    // Error inserting token
    return false;
  }

  // 4.  Send an email to the user with the reset link.
  //  (Implementation of the email sending part depends on your email setup)

  $subject = "Password Reset Request";
  $to = $email;
  $from = "your_app@example.com"; // Replace with your app's email address
  $reset_link = "https://your_app.com/reset-password?token=" . $token;  //  Replace with your app URL

  $message = "Click the link below to reset your password:
" . $reset_link;
  $headers = "From: " . $from . "\r
";
  $headers .= "Reply-To: " . $from . "\r
";
  $headers .= "X-Mailer: PHP/" . phpversion();

  //  Simulate sending the email (replace with your actual email sending function)
  //  mail($to, $subject, $message, $headers);  
  //  Use a proper email sending library like PHPMailer for production.

  return true;
}


// Example Usage:
//  Assume $email is the user's email address
//  $success = forgotPassword($email);

//  if ($success) {
//    echo "Password reset link sent to $email";
//  } else {
//    echo "Failed to generate password reset link.";
//  }



/**
 *  Helper function -  Replace with your actual database query function
 *  This is just an example, adapt to your database system.
 *  It's important that this function uses prepared statements
 *  to prevent SQL injection vulnerabilities.
 *
 * @param string $query The SQL query to execute.
 * @param array $params The parameters to bind to the query.
 * @return mysqli_result The result of the query.
 */
function db_query(string $query, array $params = []) {
    // Replace with your database connection and query execution code.
    // This is a placeholder, adapt it to your database setup.

    // Example using MySQLi (adapt to your database system).
    //  This is for demonstration purposes only and must be adapted to your specific database setup.
    
    // Assuming you have a $db object established.
    
    //  Always use prepared statements to prevent SQL injection!
    $stmt = $db->prepare($query);

    if ($stmt) {
        $stmt->execute($params);
        $result = $stmt->get_result();
        return $result;
    } else {
        // Handle the error
        error_log("Error preparing query: " . $query);
        return false;
    }
}
?>
