

<?php

// Ensure this is a secure file and protected from direct access.
// This is a simplified example and should be adapted for a production environment.

// 1. Database Connection (Replace with your actual details)
$db_host = 'localhost';
$db_name = 'your_database_name';
$db_user = 'your_username';
$db_password = 'your_password';

// 2.  Reset Password Form Handling (POST request)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);

    // Validation -  Important!  This is a minimal validation.  Improve as needed.
    if (empty($email)) {
        $error = "Please enter your email address.";
    } else {
        //Sanitize email to prevent injection
        $email = filter_var($email, FILTER_SANITIZE_EMAIL);

        // Check if the email exists in the database.  Case-insensitive is better.
        $stmt = $conn->prepare("SELECT id, email FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 0) {
            $error = "Email address not found.";
        } else {
            // Create a unique token for the reset link
            $reset_token = bin2hex(random_bytes(32));

            // Store the token in the database, associated with the user's email.
            $stmt = $conn->prepare("UPDATE users SET reset_token = ? WHERE email = ?");
            $stmt->bind_param("ss", $reset_token, $email);
            $stmt->execute();

            // Check if the update was successful (you'll likely have more error handling here)
            if ($stmt->affected_rows == 0) {
                $error = "Failed to generate reset token.";
            }

            if ($error) {
                $reset_token = ''; // Clear the token if there's an error
            }
        }
    }
}

// 3.  Generate Reset Link (If requested)
if (isset($_GET['token'])) {
    $token = trim($_GET['token']);

    // Check if the token exists in the database
    $stmt = $conn->prepare("SELECT id, email FROM users WHERE reset_token = ?");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        $userId = $user['id'];
        $email = $user['email'];

        // Generate a reset password link
        $reset_link = 'http://' . $_SERVER['HTTP_HOST'] . '/reset_password.php?token=' . $token;
        echo "<p>Click the link above to reset your password.</p>";
        echo "<p>This link will expire in 30 minutes.</p>";
    } else {
        echo "<p>Invalid reset token.</p>";
    }
}

// 4. Form for requesting a password reset
?>
