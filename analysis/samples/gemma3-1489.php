

<?php

// This is a simplified example and should be adapted for production use.
// This includes security considerations and more robust error handling.

// Database connection (Replace with your actual credentials)
$db_host = 'localhost';
$db_name = 'your_database_name';
$db_user = 'your_database_user';
$db_pass = 'your_database_password';

// Check if the request is a password reset request
if (isset($_POST['email']) && isset($_POST['reset_token'])) {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL); // Sanitize email
    $reset_token = filter_var($_POST['reset_token'], FILTER_SANITIZE_STRING); // Sanitize token

    // 1. Check if the email exists in the database
    $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "SELECT id, email FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email); // 's' indicates a string parameter
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        $stmt->close();
        $conn->close();
        echo json_encode(['success' => false, 'message' => 'Email not found']);
        exit;
    } else {
        // 2. Check if the reset token exists for this email
        $user_id = $result->fetch_assoc()['id'];  // Get user ID from the result

        // You'd typically store the reset token in the database associated with the user record
        // For this example, we'll assume the token is stored in a table called 'reset_tokens'
        // (You'll need to create this table)
        $reset_token_sql = "SELECT id, user_id, token, expiry FROM reset_tokens WHERE user_id = ? AND token = ?";

        $reset_stmt = $conn->prepare($reset_token_sql);
        $reset_stmt->bind_param("is", $user_id, $reset_token);
        $reset_stmt->execute();

        $reset_result = $reset_stmt->get_result();

        if ($reset_result->num_rows == 0) {
            $reset_stmt->close();
            $conn->close();
            echo json_encode(['success' => false, 'message' => 'Invalid reset token']);
            exit;
        } else {
            // 3. Token is valid and email exists - Reset the password

            // In a real application, you would set the password in the user table.
            // Here, we'll just simulate a password reset.
            $result_stmt->close(); // Close the original statement
            $conn->close();

            echo json_encode(['success' => true, 'message' => 'Password reset successful.  Check your email.']);
        }
    }
} else {
    // Invalid request
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
}

?>
