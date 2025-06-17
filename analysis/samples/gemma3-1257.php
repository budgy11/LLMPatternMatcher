

<?php

// Database connection details (replace with your actual credentials)
$host = 'localhost';
$username = 'your_username';
$password = 'your_password';
$database = 'your_database_name';

// Function to reset password
function forgotPassword($email, $newPassword) {
    // Connect to the database
    $conn = new mysqli($host, $username, $password, $database);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Hash the new password
    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

    // SQL query to update the user's password
    $sql = "UPDATE users SET password = '$hashedPassword' WHERE email = '$email'"; 

    if ($conn->query($sql) === TRUE) {
        return true;
    } else {
        return false;
    }

    // Close the connection
    $conn->close();
}

// Example usage (This would typically be from a form submission)
// Assuming the email and new password are received from a form
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $newPassword = $_POST["new_password"];

    if (!empty($email) && !empty($newPassword)) {
        if (forgotPassword($email, $newPassword)) {
            echo "Password reset successfully! Please check your email.";
        } else {
            echo "Failed to reset password.  Please try again.";
        }
    } else {
        echo "Please fill in all fields.";
    }
} else {
    // If not a POST request, display a form to initiate password reset.
    echo "<form method='post' action=''>";
    echo "<label for='email'>Email:</label><br>";
    echo "<input type='email' id='email' name='email' required><br><br>";

    echo "<label for='new_password'>New Password:</label><br>";
    echo "<input type='password' id='new_password' name='new_password' required><br><br>";

    echo "<input type='submit' value='Reset Password'>";
    echo "</form>";
}

?>
