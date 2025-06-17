        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>
        <br><br>
        <input type="submit" value="Reset Password">
    </form>

</body>
</html>


<?php

// Assuming you have a database connection established
// (e.g., using mysqli or PDO)
// and a table named 'users' with 'email' and 'password' columns.

// Function to handle the forgot password process
function forgot_password($email, $new_password, $db_connection) {
    // 1. Check if the email exists in the database
    $query = "SELECT id, email FROM users WHERE email = ?";
    $stmt = $db_connection->prepare($query);
    $stmt->bind_param("s", $email);  // "s" indicates a string parameter
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Email exists, proceed to reset password

        // 2. Hash the new password (IMPORTANT: Never store passwords in plain text!)
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

        // 3. Update the password in the database
        $query = "UPDATE users SET password = ? WHERE email = ?";
        $stmt = $db_connection->prepare($query);
        $stmt->bind_param("ss", $hashed_password, $email);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            // Password updated successfully
            return true;
        } else {
            // Something went wrong while updating the password
            return false;
        }

    } else {
        // Email does not exist
        return false;
    }

    // Always close the statement
    $stmt->close();
}

// --- Example Usage (Illustrative) ---

//  (Remember to replace with your actual database connection details)
//  $db_connection = new mysqli("localhost", "username", "password", "database_name");
//  if ($db_connection->connect_error) {
//     die("Connection failed: " . $db_connection->connect_error);
//  }


// Example 1: Successful password reset
// $email = "test@example.com";
// $new_password = "new_secure_password";
// $success = forgot_password($email, $new_password, $db_connection);
// if ($success) {
//    echo "Password reset successful for " . $email . "
";
// } else {
//    echo "Password reset failed for " . $email . "
";
// }


// Example 2: Email not found
// $email = "nonexistent@example.com";
// $new_password = "some_password";
// $success = forgot_password($email, $new_password, $db_connection);
// if ($success) {
//    echo "Password reset successful for " . $email . "
";
// } else {
//    echo "Password reset failed for " . $email . " (Email not found)
";
// }

// Close the database connection (VERY IMPORTANT!)
// $db_connection->close();

?>
