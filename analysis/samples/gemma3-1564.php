
<!DOCTYPE html>
<html>
<head>
    <title>Forgot Password</title>
</head>
<body>

    <h1>Forgot Password</h1>

    <form method="post" action="">
        <label for="email">Enter your email:</label>
        <input type="email" id="email" name="email" required>
        <button type="submit">Reset Password</button>
    </form>

</body>
</html>


<?php

// Assuming you have a database connection established and a 'users' table 
// with a 'email' column.  Replace these placeholders with your actual details.

// Database connection details
$host = 'localhost';
$username = 'your_username';
$password = 'your_password';
$database = 'your_database_name';

// Function to reset password
function forgot_password($email, $host, $username, $password, $database) {
    // 1. Database Connection
    $conn = new mysqli($host, $username, $password, $database);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // 2. Check if the user exists
    $stmt = $conn->prepare("SELECT id, email FROM users WHERE email = ?");
    $email = $conn->real_escape_string($email); // Sanitize input
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        $stmt->close();
        return false; // User not found
    }

    $user = $result->fetch_assoc();
    $stmt->close();

    // 3. Generate a unique token (important for security!)
    $token = bin2hex(random_bytes(32));

    // 4. Store the token in the database, associated with the user.
    $stmt = $conn->prepare("UPDATE users SET password_reset_token = ?, password_reset_token_expiry = ? WHERE email = ?");
    $token = $conn->real_escape_string($token);
    $expiry = date('Y-m-d H:i:s', time() + 3600); // Token expires in 1 hour.  Adjust as needed.
    $token = $conn->real_escape_string($token);

    $stmt->bind_param("sis", $token, $expiry, $email);
    $stmt->execute();

    if (!$stmt->affected_rows > 0) { //Check if the update was successful
        $stmt->close();
        return false;
    }
    $stmt->close();


    // 5. Send the password reset email
    $to = $email;
    $subject = "Password Reset Request";
    $message = "Please reset your password by clicking on this link: " . $_SERVER['REQUEST_SCHEME'] . "://yourdomain.com/reset_password.php?token=" . $token; // Use your domain and the token
    $headers = "From: your_email@example.com";

    mail($to, $message, $headers);

    // 6. Return true to indicate success
    return true;
}



// Example Usage (simulated - for testing)
// In a real application, this would come from the form submission.
// $_POST['email'] = "test@example.com";  // Example email

// if (isset($_POST['reset_email'])) {
//     $email = $_POST['reset_email'];
//     if (forgot_password($email, $host, $username, $password, $database)) {
//         echo "Password reset email sent to " . $email;
//     } else {
//         echo "User not found or error sending email.";
//     }
// }
?>
