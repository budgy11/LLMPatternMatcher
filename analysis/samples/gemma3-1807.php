    <label for="email">Enter your email:</label>
    <input type="email" id="email" name="email" required>
    <button type="submit">Reset Password</button>
  </form>

</body>
</html>


<?php

// This is a simplified example and should be used in a production environment
// with proper security measures.

// Database connection (replace with your actual credentials)
$host = 'localhost';
$username = 'your_db_user';
$password = 'your_db_password';
$database = 'your_database_name';

// Reset token generation function (should use a strong, random function)
function generateRandomString($length = 32) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyz';
    $randomString = '';
    $charactersLength = strlen($characters);
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}


// Reset password function
function forgotPassword($email, $host, $username, $password, $database) {
    // 1. Validate Email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return 'Invalid email address.';
    }

    // 2. Check if user exists
    $stmt = $conn->prepare("SELECT id, email FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        $stmt->close();
        return 'User not found.';
    }

    $user = $result->fetch_assoc();
    $userId = $user['id'];
    $stmt->close();



    // 3. Generate a unique token
    $resetToken = generateRandomString();

    // 4.  Create a reset token record in the database
    $stmt = $conn->prepare("INSERT INTO password_resets (user_id, token, expiry) VALUES (?, ?, ?)");
    $stmt->bind_param("iii", $userId, $resetToken, time() + 60); // Expires in 60 minutes
    $result = $stmt->execute();

    if ($result === false) {
        // Handle database errors here (e.g., try again, log error)
        error_log("Error creating password reset record: " . error_get_last()['message']);
        return 'Failed to generate reset token.';
    }

    $resetId = $conn->insert_id; // Get the ID of the inserted row.  Important for later retrieval.
    $stmt->close();


    // 5.  Send reset email
    $subject = 'Password Reset';
    $message = "Click on this link to reset your password: " . '<a href="' . $_SERVER['PHP_SELF'] . '?reset=' . $resetToken . '" >Reset Password</a>';
    $headers = "From: your_email@example.com" . "\r
";

    if(mail($email, $subject, $message, $headers)) {
        return 'Password reset email has been sent.';
    } else {
        return 'Failed to send password reset email.';
    }
}


// Example Usage (Simulated - replace with your form submission)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];

    // Replace this with your actual database connection
    $conn = new mysqli($host, $username, $password, $database);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }


    $result = forgotPassword($email, $host, $username, $password, $database);
    echo $result;
}

?>
