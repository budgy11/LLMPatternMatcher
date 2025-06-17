
<!--  reset_password.php (example - you'll need to implement the actual logic) -->
<?php
// This is a simplified example.  Implement proper validation and security.

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $token = $_POST["token"];
    $newPassword = $_POST["new_password"];
    $confirmPassword = $_POST["confirm_password"];

    // 1. Check if the token exists in the password_resets table
    $conn = new mysqli($host, $username, $password, $database);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $stmt = $conn->prepare("SELECT user_id, token, expiry FROM password_resets WHERE token = ?");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        echo "Invalid reset token.";
    } else {
        $reset = $result->fetch_assoc();
        $userId = $reset['user_id'];
        $expiry = $reset['expiry'];

        // 2. Validate the token and expiry
        if (time() > $expiry) {
            echo "Token expired.";
        } else {
            // 3. Update the user's password in the users table
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);  //Use a strong hashing function

            $stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
            $stmt->bind_param("ss", $hashedPassword, $userId);

            if ($stmt->execute() === false) {
                echo "Error updating password: " . $stmt->error;
            } else {
                // 4. Delete the reset token from the password_resets table
                $stmt = $conn->prepare("DELETE FROM password_resets WHERE user_id = ?");
                $stmt->bind_param("i", $userId);
                $stmt->execute();

                // 5.  Session handling or redirect
                echo "Password reset successfully.  Please log in.";
            }

            $stmt->close();
        }

        $conn->close();
    }
}
?>
