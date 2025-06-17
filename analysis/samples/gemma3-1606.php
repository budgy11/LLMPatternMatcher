

<?php

// Assuming you have a database connection established
// (e.g., $db = new PDO(...) or similar)

// Function to handle the forgot password process
function forgot_password($email) {
    // 1. Validate Email (Important for security!)
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return "Invalid email address.";
    }

    // 2. Generate a Unique Token
    $token = bin2hex(random_bytes(32));  // More secure random token

    // 3. Store the Token in the Database (associated with the email)
    // This is where you'd insert a record into your users table
    // with columns like 'email', 'token', and 'token_expiry'
    // This example assumes a 'users' table with 'email' and 'password' columns.
    //  Adjust to your actual database schema.

    // Assuming you have a database connection called $db
    try {
        $stmt = $db->prepare("INSERT INTO users (email, token, token_expiry) VALUES (:email, :token, :expiry)");
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':token', $token);
        $stmt->bindParam(':expiry', date('Y-m-d H:i:s', strtotime('+30 minutes'))); // Token expires after 30 minutes. Adjust as needed.
        $stmt->execute();
    } catch (PDOException $e) {
        return "Error inserting token into database: " . $e->getMessage();
    }

    // 4. Send an Email with the Reset Link
    $subject = "Password Reset Request";
    $to = $email;
    $headers = "From: your_email@example.com" . "\r
";
    $link = "http://yourwebsite.com/reset_password.php?token=$token"; // Replace with your actual reset password page URL.  **Use HTTPS in production!**

    $message = "Click the link below to reset your password:
" . $link;

    mail($to, $message, $headers);

    return "Password reset link has been sent to your email address. Please check your inbox (and spam folder!).";
}


// Example Usage (This is just for demonstration - not for production)
// $email = "testuser@example.com"; // Replace with the user's email
// $result = forgot_password($email);
// echo $result;
?>
