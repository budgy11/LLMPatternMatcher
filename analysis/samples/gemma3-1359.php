

<?php

// Database connection details (Replace with your actual credentials)
$host = "localhost";
$username = "your_db_username";
$password = "your_db_password";
$database = "your_database_name";

// Include the database connection file (recommended)
// Or, use the following code to establish the connection directly:
// $conn = new mysqli($host, $username, $password, $database);
// if ($conn->connect_error) {
//     die("Connection failed: " . $conn->connect_error);
// }


// Function to handle password reset request
function forgotPassword($email)
{
    // 1.  Check if the email exists in the user table
    $conn = new mysqli($host, $username, $password, $database);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $query = "SELECT id, email FROM users WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);  // "s" indicates a string parameter
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        // Email doesn't exist
        $stmt->close();
        $conn->close();
        return false;
    } else {
        // Email exists, generate a unique token and set a password reset link
        $token = bin2hex(random_bytes(32)); // Generate a random, secure token
        $reset_link = "https://yourwebsite.com/reset-password?token=" . $token; // Replace with your website URL

        //  SQL Query to update the user with the token and reset link
        $query = "UPDATE users SET reset_token = ?, reset_link = ? WHERE email = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("sss", $token, $reset_link, $email);
        if ($stmt->execute()) {
            // Password reset link sent to the user's email
            $stmt->close();
            $conn->close();
            return true;
        } else {
            // Error during update
            $stmt->close();
            $conn->close();
            return false;
        }
    }
}


// Example Usage (For testing - not for production)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];

    if (empty($email)) {
        echo "Error: Email is required.";
    } else {
        if (forgotPassword($email)) {
            echo "Password reset link sent to your email.";
        } else {
            echo "Error: Could not reset password.";
        }
    }
}
?>
