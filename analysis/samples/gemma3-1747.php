    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required>
    <button type="submit">Request Password Reset</button>
  </form>

</body>
</html>


<?php

// This is a simplified example.  In a production environment, 
// you MUST use secure methods to handle password resets,
// including:
//  - Generating strong, random tokens
//  - Storing tokens securely (e.g., bcrypt)
//  - Sending emails over HTTPS
//  - Implementing rate limiting to prevent abuse.
//  - Logging all reset attempts.

// **WARNING:** This code is for demonstration purposes only and is NOT suitable for production use
// without significant security enhancements.

// Database connection (Replace with your database credentials)
$host = 'localhost';
$username = 'your_db_user';
$password = 'your_db_password';
$database = 'your_db_name';

// Function to handle the forgot password request
function forgotPassword($email) {
    // 1. Check if the email exists in the database
    $conn = new mysqli($host, $username, $password, $database);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "SELECT id, username FROM users WHERE email = '$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $user_id = $result->fetch_assoc()['id'];
        $username = $result->fetch_assoc()['username'];

        // 2. Generate a unique, random token
        $token = bin2hex(random_bytes(32));

        // 3. Store the token in the database, linked to the user
        $sql = "INSERT INTO password_resets (user_id, token, expires_at) VALUES ($user_id, '$token', NOW() + INTERVAL 1 HOUR)";
        if (!$conn->query($sql)) {
            error_log("Error inserting token: " . $conn->error); // Log the error
            return false;
        }

        // 4. Send an email to the user with a reset link
        $reset_link = "http://yourwebsite.com/reset-password?token=$token&user_id=$user_id"; // Replace with your website URL
        $to = $email;
        $subject = 'Password Reset';
        $message = "Click the link below to reset your password: " . $reset_link;
        $headers = "From: Your Website <noreply@yourwebsite.com>";

        if (mail($to, $subject, $message, $headers)) {
            return true;
        } else {
            error_log("Error sending email: " . mail($to, $subject, $message, $headers)); // Log the error
            return false;
        }
    } else {
        return false; // Email not found
    }
}

// Example Usage (This would typically be handled by a form submission)
if (isset($_POST['email'])) {
    $email = $_POST['email'];
    if (forgotPassword($email)) {
        echo "Password reset email has been sent to $email";
    } else {
        echo "Error: Password reset email could not be sent.  Please check your email or contact support.";
    }
}

?>
