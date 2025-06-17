

<?php

// Assuming you have a database connection established
// (e.g., $db = mysqli_connect("host", "user", "password", "database");)

/**
 * Resets a user's password.
 *
 * @param string $email The user's email address.
 * @param string $new_password The new password.
 * @return bool True if password reset was successful, false otherwise.
 */
function forgot_password(string $email, string $new_password) {
    // 1. Validate input (important for security)
    if (empty($email) || empty($new_password)) {
        return false; // Or throw an exception - better practice for robust applications
    }

    // 2. Check if the user exists
    $stmt = mysqli_prepare($db, "SELECT id FROM users WHERE email = ?");
    mysqli_stmt_bind_param($stmt, "s", $email); // 's' for string
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) == 0) {
        mysqli_stmt_close($stmt);
        return false; // User not found
    }

    $user_id = mysqli_fetch_assoc($result)['id'];
    mysqli_stmt_close($stmt);

    // 3. Hash the new password
    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

    // 4. Update the password in the database
    $stmt = mysqli_prepare($db, "UPDATE users SET password = ? WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "ss", $hashed_password, $user_id);
    if (mysqli_stmt_execute($stmt)) {
        mysqli_stmt_close($stmt);
        return true;
    } else {
        // Handle database error (important!)
        error_log("Error resetting password for user: " . $email); // Log the error for debugging
        return false;
    }
}


// Example Usage (Illustrative - Replace with your actual form processing)
//  (Assuming you have a form with email and new_password fields)

//  $email = $_POST['email'];
//  $new_password = $_POST['new_password'];

//  if (forgot_password($email, $new_password)) {
//      echo "Password reset email sent.  Check your inbox.";
//      // Send email to confirm the password reset
//  } else {
//      echo "Error resetting password. Please try again.";
//  }


?>
