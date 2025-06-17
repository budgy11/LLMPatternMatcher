

<?php

// Database configuration (Replace with your actual credentials)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_database_user";
$db_password = "your_database_password";

// Function to check if the login is successful
function isLoginSuccessful($username, $password, $user_id) {
    // In a real application, you would check against a database table
    // that stores usernames and hashed passwords.
    // This example uses a simple comparison for demonstration only.
    // NEVER store plain text passwords!

    if ($username == "demo" && $password == "password") {
        // Simulate successful login and user ID retrieval
        return $user_id;
    }

    return false;
}

// Function to handle the login process
function loginUser($username, $password) {
    // Validate input (important for security)
    if (empty($username) || empty($password)) {
        return "Username and password cannot be empty.";
    }

    // Hash the password (for security - this is a basic example)
    // In a real application, use a strong hashing algorithm like bcrypt or Argon2.
    $hashed_password = password_hash($password, PASSWORD_DEFAULT); // Use password_hash()

    // Simulate database query (replace with your actual database query)
    $user_id = isLoginSuccessful($username, $hashed_password, 123); // Replace 123 with actual user_id or result of query.

    if ($user_id) {
        // Login successful
        $_SESSION["user_id"] = $user_id; // Store the user ID in the session
        return true;
    } else {
        // Login failed
        return false;
    }
}


// Example usage (for demonstration)
if (isset($_POST['login_username']) && isset($_POST['login_password'])) {
    $username = $_POST['login_username'];
    $password = $_POST['login_password'];

    $login_result = loginUser($username, $password);

    if ($login_result === true) {
        echo "Login successful!  User ID: " . $login_result;
    } else {
        echo "Login failed.  " . $login_result;
    }
}


?>
