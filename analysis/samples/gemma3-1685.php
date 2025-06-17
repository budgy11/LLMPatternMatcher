

<?php
// Assuming you have the forgotPassword() function defined above.
$host = 'localhost';
$username = 'your_db_username';
$password = 'your_db_password';
$database = 'your_database_name';

// Get the token from the URL
$token = $_GET['token'];

// Verify the token
$conn = new mysqli($host, $username, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT u.id, u.email FROM users u
        INNER JOIN password_reset_tokens r ON u.id = r.user_id
        WHERE r.token = '$token'";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $user_id = $result->fetch_assoc()['id'];
    $user_email = $result->fetch_assoc()['email'];

    // Update password
    $new_password = $_POST['new_password']; // Get new password from form
    $sql_update = "UPDATE users SET password = '$new_password' WHERE id = '$user_id'";
    if (!$conn->query($sql_update)) {
        error_log("Error updating password: " . $conn->error);
    }

    // Delete token after successful password update
    $sql_delete_token = "DELETE FROM password_reset_tokens WHERE user_id = '$user_id' AND token = '$token'";
    if (!$conn->query($sql_delete_token)) {
        error_log("Error deleting token: " . $conn->error);
    }

    echo "Password reset successful.  Please log in with your new password.";

} else {
    echo "Invalid reset token.";
}

$conn->close();
?>
