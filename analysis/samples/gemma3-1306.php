

<?php

// This is a basic example and should be implemented with security in mind.
//  * Never store passwords directly.  Always hash them.
//  * Use strong password reset mechanisms (e.g., email verification, OTP).
//  * This is for demonstration purposes only.

// Configuration (Replace with your database credentials and paths)
$db_host = 'localhost';
$db_name = 'your_database_name';
$db_user = 'your_username';
$db_password = 'your_password';

// Function to generate a random token
function generateRandomToken() {
    return bin2hex(random_bytes(32));
}

// Function to reset password (Simplified Example - Use a proper database interaction and security practices)
function forgotPassword($email, $token) {
    // 1. Check if email exists in the database
    $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $query = "SELECT id, email FROM users WHERE email = '$email'";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $userId = $user['id'];
        $userEmail = $user['email'];

        // 2. Generate a token for the reset request
        $resetToken = generateRandomToken();

        // 3. Store the token in the database, associated with the user's ID
        $insertQuery = "INSERT INTO password_resets (user_id, token, expires_at) 
                         VALUES ($userId, '$resetToken', NOW() + INTERVAL 24 HOUR)"; // Token expires after 24 hours
        if (!$conn->query($insertQuery)) {
            // Handle the error.  Ideally log the error.
            echo "Error inserting reset token: " . $conn->error;
            return false;
        }

        // 4. Send an email with a link to reset the password
        $resetLink = 'http://yourwebsite.com/reset-password?token=' . $token;  // Replace with your website URL
        //  Implement email sending logic here using a library or your own SMTP setup.
        //  Example (placeholder):
        //  mail($userEmail, "Reset Password", "Click here: " . $resetLink);


        return true; // Success
    } else {
        return false; // User not found
    }

    $conn->close();
}

// Example Usage (Demonstration)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    //  In a real application, you would validate the email format and other inputs here.
    if (!empty($email)) {
        $resetResult = forgotPassword($email, generateRandomToken());

        if ($resetResult) {
            echo "Password reset link sent to your email address.";
        } else {
            echo "An error occurred while generating the reset link.  Please try again.";
        }
    } else {
        echo "Please enter your email address.";
    }
}

?>
