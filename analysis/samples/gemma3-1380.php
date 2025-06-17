        <button type="submit">Update Password</button>
    </form>
</body>
</html>
-->


<?php

// This is a simplified example.  In a production environment,
// you would likely store passwords as hashes and use a more secure
// method for generating and handling reset tokens.

// Database connection (replace with your actual credentials)
$host = "localhost";
$username = "your_db_username";
$password = "your_db_password";
$database = "your_db_name";

// --- Function to reset password ---
function forgot_password($email, $password_reset_token, $expiration_time = 3600) { // Default expiration time is 1 hour
    // 1. Check if the email exists
    $conn = new mysqli($host, $username, $password, $database);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $result = $conn->query("SELECT id, email FROM users WHERE email = '$email'");

    if ($result->num_rows > 0) {
        $user_id = $result->fetch_assoc()['id'];
        $result->free();

        // 2. Generate a unique, time-based token (for security)
        $token = bin2hex(random_bytes(32)); // Use strong random bytes.  More secure than rand()

        // 3.  Store the token in the database, associated with the user.
        //    Important:  Use prepared statements to prevent SQL injection.
        $sql = "UPDATE users SET password_reset_token = '$token', password_reset_expires = NOW() + INTERVAL $expiration_time SECOND WHERE email = '$email'";
        if (!$conn->query($sql)) {
            // Handle errors if the update fails (e.g., log an error)
            echo "Error updating user: " . $conn->error;
            return false;
        }


        // 4.  Send an email with a link to the reset page.
        //    This part is highly dependent on your email setup.
        //    For simplicity, we'll just print a message here.  Replace with
        //    your actual email sending logic.
        echo "A password reset link has been sent to your email address: " . $email . "<br>";
        echo "Please use this link:  http://yourdomain.com/reset_password?token=$token"; //Replace yourdomain.com


        // 5.  Return true to indicate success.
        return true;


    } else {
        // Email not found
        return false;
    }

    $conn->close();
}


// --- Example Usage (for testing/demo purposes) ---

// Simulate a forgotten password request
//  replace "test@example.com" with a valid email
//  this is for testing, do not use this in a real application without proper security.
$email = "test@example.com";  // Replace with a real email (or use a test email)

if (forgot_password($email, "random_token_for_test")) {
    echo "Password reset email sent (test).  Check the email!";
} else {
    echo "Password reset email could not be sent.  User may not exist or there was an error.";
}

?>
