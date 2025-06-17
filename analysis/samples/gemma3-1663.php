    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required>
    <button type="submit">Reset Password</button>
  </form>

</body>
</html>


<?php

// Database connection details (replace with your actual values)
$db_host = 'localhost';
$db_name = 'your_database_name';
$db_user = 'your_database_username';
$db_password = 'your_database_password';

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get email from the form
    $email = $_POST["email"];

    // Validate email format (basic validation, consider more robust methods)
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format.";
    } else {
        // Attempt to reset password (implementation depends on your setup)
        // This is a placeholder -  replace with your actual logic
        $reset_password_key = generate_unique_key(); // Generate a unique key for the reset token
        $expiry_time = time() + (24 * 60 * 60); // Token expires after 24 hours

        //  Create a query to update the user's record with the reset token and expiry time
        $sql = "UPDATE users SET reset_token = ?, reset_token_expiry = ? WHERE email = ?";

        // Prepare the statement
        $stmt = $conn->prepare($sql);

        // Bind the parameters
        $stmt->bind_param("sii", $reset_password_key, $expiry_time, $email);


        // Execute the query
        if ($stmt->execute()) {
            // Send an email with the reset link
            $subject = "Password Reset Link";
            $headers = "From: your_email@example.com" . "\r
";
            $message = "Click this link to reset your password: " . $_SERVER["REQUEST_URI"] . "?reset_token=" . $reset_password_key;
            $headers .= "MIME-Version: 1.0" . "\r
";
            $headers .= "Content-type: text/html; charset=UTF-8" . "\r
";

            mail($email, $subject, $message, $headers);

            $error = "Password reset link has been sent to your email address.";
        } else {
            $error = "Error resetting password. Please try again.";
        }
    }
}
?>
