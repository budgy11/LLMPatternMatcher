    <button type="submit">Reset Password</button>
</form>

</body>
</html>


<?php

// Assuming you have a database connection established and a 'users' table
// with a 'email' column.  Adjust this to match your actual database setup.

// Function to reset password
function forgot_password($email, $new_password, $token, $db_connection) {
    // 1. Check if the email exists in the database
    $stmt = $db_connection->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        return false; // Email not found
    }

    // 2. Generate a secure token (Important for security)
    $token = bin2hex(random_bytes(32)); // Generate a secure, random token
    // Use a strong random number generator for better security.


    // 3. Hash the new password
    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

    // 4. Update the user's password in the database
    $stmt = $db_connection->prepare("UPDATE users SET password = ?, reset_token = ?, reset_token_expiry = ? WHERE id = ?");
    $stmt->execute([$hashed_password, $token, time(), $user['id']]);

    // 5. Store the token and expiry time in the database
    //   - Reset token to hold the token generated above.
    //   - Reset token expiry to the current time (for token expiration)

    // 6.  Send an email to the user with instructions and a link.  (Not implemented here)
    //  You'll need to implement an email sending function or use a library
    //  to send the password reset link.  Include the token in the link.


    return true; // Password reset successful
}


// Example Usage (Illustrative - adapt to your application)
//
//  1. Get input from the form
// $email = $_POST['email'];
// $new_password = $_POST['new_password'];
// $token = $_POST['token'];
//
//  2.  Establish DB connection.
//  $db_connection = new PDO("mysql:host=localhost;dbname=your_database_name", "username", "password");
//
//  3.  Call the function
// if (forgot_password($email, $new_password, $token, $db_connection)) {
//     echo "Password reset email has been sent.  Please check your inbox.";
// } else {
//     echo "Invalid email or token.";
// }

?>
