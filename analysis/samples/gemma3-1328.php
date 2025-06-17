    <label for="email">Email Address:</label>
    <input type="email" id="email" name="email" required>
    <button type="submit">Request Password Reset</button>
  </form>

</body>
</html>


$query = "SELECT * FROM forgot_password_tokens WHERE token = '$token' AND hashed_token = '$hashed_token'";
$result = mysqli_query($db, $query);

if ($result) {
    $user_data = mysqli_fetch_assoc($result);
    $expiration_time = strtotime('+1 hour', $user_data['created_at']); // Expire after 1 hour
    $current_time = time();

    if ($current_time > $expiration_time) {
        // Token has expired
        echo "Token has expired. Please request a new password reset.";
        return false;
    }
    // ... (Continue with password reset process)
}


<?php

// Assuming you have a database connection established
// $db = new mysqli($host, $username, $password, $database);
// if ($db->connect_error) {
//     die("Connection failed: " . $db->connect_error);
// }

// Function to handle the forgot password process
function forgotPassword($email, $newPassword, $db) {
    // 1. Validate Email (Optional but recommended)
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return "Invalid email format.";
    }

    // 2. Check if the email exists in the database
    $stmt = $db->prepare("SELECT id, email FROM users WHERE email = ?");
    $email_to_check = $email;
    $stmt->bind_param("s", $email_to_check); // 's' indicates a string parameter
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        $stmt->close();
        return "User not found.";
    }

    $user = $result->fetch_assoc();
    $stmt->close();

    // 3. Generate a unique token or use a hashed password reset token
    $token = bin2hex(random_bytes(32)); // Generate a random, secure token.  Consider a more robust method if security is paramount.

    // 4. Hash the token for security
    $hashed_token = password_hash($token, PASSWORD_DEFAULT);

    // 5. Update the user's record with the hashed token
    $stmt = $db->prepare("UPDATE users SET password_reset_token = ? WHERE email = ?");
    $stmt->bind_param("ss", $hashed_token, $email);
    $stmt->execute();

    if (!$stmt->affected_rows > 0) {
        $stmt->close();
        return "Failed to update password reset token.";
    }

    $stmt->close();

    // 6. Send an email with the reset link
    $subject = "Password Reset Link";
    $message = "Please click on the following link to reset your password: " . "<a href='" . $_SERVER['PHP_SELF'] . "?reset=" . urlencode($token) . "'>Reset Password</a>"; // Include the reset token in the URL
    $headers = "From: your_email@example.com";  // Replace with your email address

    mail($email, $subject, $message, $headers);

    // 7. Return a success message
    return "Password reset link sent to your email.";
}

// Example Usage (Simulated for demonstration)
//  This would typically be handled by a form submission.
//  For demonstration, let's simulate getting email and new password.
//  In a real application, you'd get this data from a form.
$email = "test@example.com"; // Replace with the user's email
$newPassword = "P@sswOrd123";  // Replace with the desired new password

// Simulate the database connection
// For demonstration, we create a mock database object
class MockDB {
    public function prepare($query) {
        // In a real application, this would use a prepared statement
        // For demonstration, we'll just return a dummy result
        return null;
    }

    public function bind_param($type, $value) {
        // Do nothing for demonstration
    }

    public function execute() {
        // Dummy result for demonstration
        return array(
            'num_rows' => 1, // Assume user exists
            'fetch_assoc' => function() {
                return array(
                    'id' => 1,
                    'email' => 'test@example.com'
                );
            }
        );
    }

    public function affected_rows() {
        return 1;
    }

    public function close() {
        // Do nothing for demonstration
    }
}
$db = new MockDB();


$result = forgotPassword($email, $newPassword, $db);
echo $result;

?>
