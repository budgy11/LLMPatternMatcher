

<?php

// Database connection details (replace with your actual credentials)
$db_host = 'localhost';
$db_name = 'your_database_name';
$db_user = 'your_database_user';
$db_password = 'your_database_password';

// ---  Forgot Password Function ---
function forgotPassword($email) {
    // 1. Validate Email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return 'Invalid email address.';
    }

    // 2. Check if User Exists
    $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

    if ($conn->connect_error) {
        return 'Connection failed: ' . $conn->connect_error;
    }

    $sql = "SELECT id, password, email FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email); // 's' indicates a string parameter
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        $stmt->close();
        $conn->close();
        return 'User not found.';
    } else {
        $user = $result->fetch_assoc();
        $stmt->close();
        $conn->close();

        // 3. Generate a Unique Token (for security)
        $token = bin2hex(random_bytes(32));

        // 4.  Store Token in Database
        $sql = "INSERT INTO password_resets (user_id, token, expiry) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iii", $user['id'], $token, time() + 60); // Expiry after 60 minutes
        $stmt->execute();
        $stmt->close();

        // 5. Send Reset Link Email (Implement your email sending logic here)
        //  This is just a placeholder - replace with your actual email sending function
        $reset_link = 'http://yourwebsite.com/reset_password?token=' . $token;
        $subject = 'Password Reset Request';
        $message = 'Click this link to reset your password: ' . $reset_link;
        //  Use a function like sendEmail($to, $subject, $message);
        //  Example (replace with your actual email sending)
        //  sendEmail($user['email'], $subject, $message);
        return $reset_link;

    }
}

// --- Example Usage (for testing) ---
//  **IMPORTANT:**  This is just for demonstration.  Do NOT use this
//  in a production environment without proper security measures and
//  email integration.

// Example:  If you wanted to trigger the function from a form submission,
//  you'd likely use something like this:

// $email = $_POST['email'];
// $reset_link = forgotPassword($email);
// echo $reset_link;

?>
