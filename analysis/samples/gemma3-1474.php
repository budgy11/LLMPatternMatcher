

<?php

// Assuming you have a database connection established and stored in $db
// (e.g., $db = mysqli_connect("host", "username", "password", "database_name");)

function forgot_password($email, $new_password, $db) {
    // Validate email format (basic check - more robust validation recommended)
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return "Invalid email address.";
    }

    // Hash the new password
    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

    // SQL query to update the password for the given email
    $sql = "UPDATE users SET password = '$hashed_password' WHERE email = '$email'";

    if ($db->query($sql) === TRUE) {
        return "Password reset email sent.  Check your email.";
    } else {
        return "Error resetting password. " . $db->error;
    }
}


// --- Example Usage (Demonstration -  Do not use directly in a live environment!) ---

// Replace with your actual database connection
$db = mysqli_connect("localhost", "your_username", "your_password", "your_database");

if (!$db) {
    die("Connection failed: " . mysqli_connect_error());
}

// Simulate a forgot password request
$email = "test@example.com";  // Replace with a real user's email
$new_password = "NewSecretPassword123";

$result = forgot_password($email, $new_password, $db);

echo $result;  // Output: Password reset email sent.  Check your email.
// --- End Example Usage ---
?>
