

<?php
session_start(); // Start the session to store user data

/**
 * User Login Function
 *
 * This function handles user login.
 *
 * @param string $username The username for login.
 * @param string $password The password for login.
 * @return bool True if login is successful, false otherwise.
 */
function loginUser($username, $password)
{
    // **IMPORTANT SECURITY NOTE:**
    // This example uses a simple hardcoded password.
    // **NEVER** store passwords in plain text in a real application.
    // Use a strong hashing algorithm like bcrypt or Argon2.
    //  You should also use password_verify() to compare the hashed password.

    $validUsers = [
        'john.doe' => 'password123',
        'jane.smith' => 'securePass123',
    ];

    // Check if the username exists and the password matches
    if (isset($validUsers[$username])) {
        if (password_verify($password, $validUsers[$username])) { // Use password_verify()
            // Login successful
            $_SESSION['username'] = $username; // Store the username in the session
            return true;
        } else {
            // Password mismatch
            return false;
        }
    } else {
        // Username doesn't exist
        return false;
    }
}

// Example Usage (Demonstration - don't use this in a real web application)
// Simulate a login form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    if (loginUser($username, $password)) {
        echo "<p>Login successful! You are now logged in as " . $username . "</p>";
        // Redirect to a protected page or display a welcome message
        header("Location: welcome.php"); // Redirect to a welcome page
        exit();
    } else {
        echo "<p>Invalid username or password.</p>";
    }
}
?>
