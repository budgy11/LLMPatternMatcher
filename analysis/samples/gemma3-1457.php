

<?php

// Assuming you have a database connection established (e.g., $db)
// and the user's email is stored in a session variable called $userEmail

// 1. Check if the user is already logged in.  If they are, don't allow a forgot password request.
if (isset($_SESSION['user_id'])) {
    // User is logged in - redirect to login page or homepage.  Don't proceed with forgot password.
    header("Location: /"); // Replace "/" with your login page URL
    exit();
}


// 2. Handle the form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];

    // Basic email validation (improve this for production)
    if (empty($email)) {
        $error = "Please enter your email address.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email address.";
    } else {
        // 3. Generate a unique reset token
        $resetToken = bin2hex(random_bytes(32));

        // 4. Store the token in the database, associated with the user's email
        //  This is a simplified example.  Use prepared statements to prevent SQL injection.
        //  Consider using a more robust hashing mechanism for security.
        $query = "INSERT INTO password_resets (user_email, reset_token, created_at) VALUES (?, ?, NOW())";
        $stmt = $db->prepare($query);
        $stmt->bind_param("s", $email, $resetToken);
        $stmt->execute();
        $stmt->close();

        // 5.  Send an email with the reset token
        $to = $email;
        $subject = "Password Reset Request";
        $message = "Click this link to reset your password: " . "<a href='" . $_SERVER['PHP_SELF'] . "?reset=" . urlencode($resetToken) . "'>Reset Password</a>";
        $headers = "From: your-website@example.com" . "\r
";

        if (mail($to, $subject, $message, $headers)) {
            echo "Password reset email has been sent.  Please check your inbox.";
        } else {
            echo "Error sending password reset email.  Please try again later.";
        }
    }
}

?>
