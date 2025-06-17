    <label for="email">Email:</label>
    <input type="email" id="email" name="email" placeholder="Your Email" required>
    <button type="submit">Reset Password</button>
  </form>

</body>
</html>


<?php

// Database connection details (Replace with your actual credentials)
$host = "localhost";
$username = "your_db_username";
$password = "your_db_password";
$database = "your_database_name";

// 1. Form Handling (GET or POST)

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];

    // 2. Input Validation
    if (empty($email)) {
        $error = "Email address is required.";
    } else {
        // 3. Query the Database
        $conn = new mysqli($host, $username, $password, $database);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $sql = "SELECT id, password, email FROM users WHERE email = '$email'";
        $result = $conn->query($sql);

        if ($result->num_rows == 0) {
            $error = "Email address not found.";
        } else {
            $user = $result->fetch_assoc();
            $userId = $user['id'];
            $hashedPassword = $user['password'];  // Important: Use the *hashed* password
            $resetLink = generateResetLink($userId);  // Generate a unique reset link

            // 4.  Send Password Reset Email (Implement this)
            $to = $email;
            $subject = "Password Reset";
            $message = "Click on the following link to reset your password: " . "<a href='" . $resetLink . "'>Reset Password</a>";
            $headers = "From: your_email@example.com" . "\r
";
            mail($to, $message, $headers);

            // You can display a success message here:
            $success = "Password reset link has been sent to your email address.";

        }
    }
}

// 5. Generate Reset Link (Important - Generate a unique link)
function generateResetLink($userId) {
    // Use a unique identifier (e.g., a random string) to create a secure reset link.
    // This is crucial for security. Don't use just the user ID.
    return 'https://yourwebsite.com/reset-password.php?id=' . urlencode($userId);  // Replace with your actual URL
}

?>
