

<?php

// Important:  This is a basic example for demonstration.
//  In a real-world application, you should NEVER directly expose
//  your database credentials or sensitive information like passwords.
//  Use environment variables and proper security measures.

// 1. Database Connection (Example - Replace with your actual database details)
$host = 'localhost';
$username = 'your_db_username';
$password = 'your_db_password';
$database = 'your_db_name';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$database", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Enable error handling
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// 2. Function to Reset Password
function forgotPassword($email, $newPassword) {
    global $pdo; // Access the PDO connection

    // Hash the new password (IMPORTANT for security!)
    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

    // Prepare the SQL query
    $stmt = $pdo->prepare("UPDATE users SET password = :password WHERE email = :email");

    // Bind parameters (prevent SQL injection)
    $stmt->bindParam(':password', $hashedPassword);
    $stmt->bindParam(':email', $email);

    // Execute the query
    if ($stmt->execute()) {
        return true; // Password reset successful
    } else {
        return false; // Password reset failed
    }
}


// 3. Example Usage (Simulated Form Handling - Adapt to your form)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $newPassword = $_POST["new_password"];

    // Validate email format (basic check - enhance for production)
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email address.";
    } else {
        if (forgotPassword($email, $newPassword)) {
            echo "Password reset email sent. Please check your email.";
        } else {
            echo "Failed to reset password. Please try again.";
        }
    }
}

?>
