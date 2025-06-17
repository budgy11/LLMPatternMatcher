    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required><br><br>

    <!--  In a real application, you would generate a random token
         and store it in the database.  For this example, we'll
         just have the user enter a token. -->
    <label for="reset_token">Reset Token:</label>
    <input type="text" id="reset_token" name="reset_token" required><br><br>

    <button type="submit">Reset Password</button>
  </form>

</body>
</html>


<?php

/**
 * Forgot Password Function
 *
 * This function handles the forgot password process.  It:
 * 1. Generates a unique, time-based token.
 * 2. Sends an email to the user with a link containing the token.
 * 3. Stores the token in the database associated with the user's ID.
 * 4.  Returns a success message or an error message.
 *
 * @param string $email        The user's email address.
 * @param string $site_url    The URL of your website.
 * @param string $reset_token_prefix  (Optional) Prefix for the reset token.  Good for security.
 *
 * @return string  A success or error message.
 */
function forgot_password(string $email, string $site_url, string $reset_token_prefix = 'reset') {

    // 1. Generate a unique, time-based token
    $token = $reset_token_prefix . md5(time());

    // 2.  Check if the email exists in the database.  You'll need a database connection here!
    // Assuming you have a database connection variable called $db
    // $user = $db->query("SELECT id, email FROM users WHERE email = '$email' LIMIT 1")->fetch_assoc();

    // This is a placeholder.  Replace with your actual database query.
    // This example assumes you have a `users` table with `id` and `email` columns.
    $user = $db->query("SELECT id, email FROM users WHERE email = '$email' LIMIT 1")->fetch_assoc();
    
    if (!$user) {
        return "Email not found.";
    }

    // 3.  Store the token in the database
    $result = $db->query("UPDATE users SET reset_token = '$token', reset_token_expiry = NOW() + INTERVAL 1 HOUR WHERE id = $user['id']");

    if (!$result) {
        return "Error updating user information.";
    }

    // 4.  Create the reset password link
    $reset_link = $site_url . "/reset_password.php?token=$token";  // Example URL
    

    // 5.  Send the password reset email.  You'll need to implement this.
    // This is a placeholder - you'll need to replace this with your email sending logic.
    $subject = "Password Reset";
    $message = "Click on the following link to reset your password: " . $reset_link;
    
    // In a real application, you'd use a function like:
    // send_email($user['email'], $subject, $message); 

    return "Password reset email sent to $email";
}


// Example Usage (replace with your actual database connection and settings)
//  This is just an example, it doesn't actually connect to a database
// and won't work as is.

// Assume $db is your database connection object (e.g., mysqli_connect)
//$db = mysqli_connect("localhost", "username", "password", "database_name");

//if (!$db) {
//    die("Database connection failed");
// }


// $email = "test@example.com";
// $reset_message = forgot_password($email, "https://example.com");
// echo $reset_message;


?>
