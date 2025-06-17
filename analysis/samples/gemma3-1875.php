

<?php

// Configuration (Important - Replace with your actual values)
$db_host = 'localhost';
$db_name = 'your_database_name';
$db_user = 'your_database_username';
$db_password = 'your_database_password';

// Function to reset password
function forgot_password($email, $new_password) {
    // 1. Connect to the database
    $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // 2. Hash the new password -  IMPORTANT for security!
    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

    // 3. Update the password in the database
    $sql = "UPDATE users SET password = '$hashed_password' WHERE email = '$email'";

    if ($conn->query($sql) === TRUE) {
        echo "New password set successfully! Please check your email for login instructions.";
    } else {
        echo "Error updating password: " . $conn->error;
    }

    $conn->close();
}


// Example usage (This is just for demonstration - you'll need a form to submit this)
// In a real application, you would handle the form submission here.
// This example shows how the function would be called.
// For a real application, you'll need to implement a form with an email field.

// Example: Let's say the user's email is 'test@example.com' and the new password is 'newPassword123'
//forgot_password('test@example.com', 'newPassword123');
?>
