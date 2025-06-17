

<?php

// Database connection details (Replace with your actual credentials)
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database";

// Function to handle password reset
function forgot_password($email) {
    // 1. Check if the email exists in the user table
    $conn = new mysqli($host, $username, $password, $database);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "SELECT id, email FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email); // "s" for string
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        $stmt->close();
        $conn->close();
        return false; // Email not found
    }

    $user = $result->fetch_assoc();
    $user_id = $user['id'];
    $user_email = $user['email'];
    $stmt->close();

    // 2. Generate a unique token
    $token = bin2hex(random_bytes(32)); // Generates a cryptographically secure random string

    // 3. Prepare the reset link
    $reset_link = "http://yourdomain.com/reset_password?token=$token&user_id=$user_id";

    // 4. Store the token and user ID in the database (temporary)
    $sql = "INSERT INTO password_resets (user_id, token, expires_at) VALUES (?, ?, NOW())";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $user_id, $token, $conn->insert_id); // 's' for string, insert_id for NOW()
    $stmt->execute();
    $stmt->close();

    // 5. Send the reset email
    $to = $email;
    $subject = "Password Reset";
    $message = "Click the link below to reset your password: " . $reset_link;
    $headers = "From: your_email@example.com\r
";

    if (mail($to, $subject, $message, $headers)) {
        return true; // Reset email sent successfully
    } else {
        // Handle email sending failure (e.g., log it)
        return false;
    }
}


// Example Usage (For demonstration purposes -  you'd typically handle this from a form submission)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];

    if (!empty($email)) {
        if (forgot_password($email)) {
            echo "Reset link has been sent to your email address.";
        } else {
            echo "Failed to send reset link. Please try again.";
        }
    } else {
        echo "Please enter your email address.";
    }
}

?>
