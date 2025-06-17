

<?php

// Database connection details (replace with your actual credentials)
$host = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "your_database_name";

// Check if the reset_password_request parameter is present
if (isset($_POST['reset_password_request'])) {
    // 1. Get the email from the form.  Validate this!
    $email = $_POST['email'];

    // **IMPORTANT:  Sanitize and Validate the Email!**
    // This is a critical security step.  Prevent SQL injection and other attacks.
    $email = filter_var($email, FILTER_SANITIZE_EMAIL);  //Sanitize
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email address.";
    }
    // 2.  Fetch the user from the database
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password); // Create PDO object

    try {
        $stmt = $pdo->prepare("SELECT id, email FROM users WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // 3.  Check if the user exists
        if ($user) {
            // 4.  Generate a unique, secure reset token
            $resetToken = bin2hex(random_bytes(32));  // Use cryptographically secure random bytes

            // 5.  Store the token in the database for the user.  Important to track expiration.
            //    Also, set expiration time (e.g., 1 hour)
            $stmt = $pdo->prepare("INSERT INTO password_resets (user_id, token, expires_at) VALUES (:user_id, :token, NOW() + INTERVAL 1 HOUR)");  //Store in password_resets table
            $stmt->bindParam(':user_id', $user['id']);
            $stmt->bindParam(':token', $resetToken);
            $stmt->execute();


            // 6.  Send the reset email (implementation omitted for brevity)
            $to = $user['email'];
            $subject = "Password Reset Request";
            $message = "Please click the following link to reset your password: <a href='" . $_SERVER['PHP_SELF'] . "?reset_token=$resetToken'>Reset Password</a>"; //  Generate dynamic link
            $headers = "From: your_email@example.com";  //Replace with your email
            mail($to, $subject, $message, $headers);

            // 7.  Success message
            echo "<p>Password reset email has been sent to $email.</p>";
        } else {
            // 8. User not found
            echo "<p>User not found.</p>";
        }
    } catch (PDOException $e) {
        echo "<p>Error: " . $e->getMessage() . "</p>"; // Handle database errors gracefully
    }
}
?>
