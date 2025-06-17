        <button type="submit">Reset Password</button>
    </form>

</body>
</html>


<?php

// Assuming you have a database connection established (e.g., $db)
// and have a 'users' table with a 'email' column.

// Function to handle password reset requests
function forgot_password($email, $token, $reset_password_link) {
    // 1. Check if the email exists in the database
    $result = mysqli_query($db, "SELECT id, email FROM users WHERE email = '$email'"); // Replace $db with your actual database connection

    if (mysqli_num_rows($result) > 0) {
        // 2. Generate a unique token and store it in the database
        $token_result = mysqli_query($db, "SELECT id FROM users WHERE email = '$email' AND reset_token = '$token'");

        if (mysqli_num_rows($token_result) > 0) {
            // Token already exists, so the user has already requested a reset.
            // You might want to handle this situation (e.g., display a message).
            return "A password reset request has already been made for this email.";
        }

        $new_token = bin2hex(random_bytes(32));  // Generate a random 32-byte token
        $insert_result = mysqli_query($db, "UPDATE users SET reset_token = '$new_token' WHERE email = '$email'");

        if (mysqli_affected_rows($insert_result) > 0) {
            // 3. Generate the password reset link (URL)
            $reset_url = $reset_password_link . "?email=" . $email . "&token=" . $new_token;

            // 4.  Optionally, send an email to the user with the reset link
            // (Implement email sending here - using PHPMailer or similar)
            // Example:
            // send_email($email, "Password Reset Link", $reset_url);

            return $reset_url; // Return the URL to the reset page
        } else {
            return "Error updating user information.";
        }
    } else {
        return "Invalid email address.";
    }
}


// Example Usage (demonstration - not a full implementation)
// $email = "test@example.com";
// $token = "some_random_token";
// $reset_password_link = "https://yourwebsite.com/reset-password";

// $reset_url = forgot_password($email, $token, $reset_password_link);

// if (strpos($reset_url, "Invalid email address") === false &&
//     strpos($reset_url, "Error updating user information") === false) {
//    echo $reset_url;  // Output the URL
// } else {
//    echo $reset_url;
//}
?>
