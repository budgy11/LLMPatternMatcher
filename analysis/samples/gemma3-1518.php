

<?php

// Database connection details (replace with your actual credentials)
$host = "localhost";
$username = "your_db_username";
$password = "your_db_password";
$database = "your_db_name";

// Function to handle the forgot password process
function forgotPassword($email) {
    // 1. Validate Email (Basic Check)
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return "Invalid email address.";
    }

    // 2. Query the database to find the user
    $conn = new mysqli($host, $username, $password, $database);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "SELECT id, password, email FROM users WHERE email = '$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $userId = $user['id'];
        $hashedPassword = $user['password']; // Store hashed password in the database

        // 3. Generate a unique token (for security - DO NOT store in database)
        $token = bin2hex(random_bytes(32));  // Secure random token

        // 4.  Store the token in a temporary table (or session)
        //  This is necessary so we can later reset the password using the token.
        //  For a production system, consider a more secure temporary storage
        //  (e.g., a Redis cache or a dedicated temporary table with expiry)
        $conn2 = new mysqli($host, $username, $password, $database);
        if ($conn2->connect_error) {
            die("Connection failed: " . $conn2->connect_error);
        }

        $sql2 = "INSERT INTO password_resets (user_id, token, expires_at) VALUES ('$userId', '$token', NOW())"; // Store in a password_resets table
        if ($conn2->query($sql2) === TRUE) {
            // 5. Send the password reset email
            $to = $email;
            $subject = "Password Reset Request";
            $message = "Click this link to reset your password: " . $_SERVER['REQUEST_SCHEME'] . "://localhost/reset_password.php?token=$token";  // IMPORTANT:  Use HTTPS for production!
            $headers = "From: your_email@example.com";  // Replace with your email address

            mail($to, $message, $headers);

            return "Password reset email sent to $email.";
        } else {
            return "Error creating password reset record.";
        }


    } else {
        return "User not found with email $email.";
    }

    $conn2->close();
    $conn->close();
}


// Example Usage (This is just a demonstration - you'll need to integrate this into your form)
// Assuming you've submitted a form with an email address
// $email = $_POST['email'];  // Get the email from the form

// $result = forgotPassword($email);
// echo $result; // Display the result (e.g., "Password reset email sent...")
?>
