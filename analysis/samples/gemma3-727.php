

<?php

/**
 * Handles user login functionality.
 *
 * This function takes username and password as input and checks
 * against a simple (and insecure!) database.  In a production
 * environment, you *MUST* use a robust authentication system
 * with hashing and salting for security.
 */

function loginUser($username, $password) {
    // **WARNING:  This is a VERY insecure example for demonstration only!**
    // In a real application, never store passwords in plain text.
    // Use password_hash() and password_verify() for secure password handling.

    // Simulate a database (replace with your actual database connection)
    $users = [
        ['username' => 'john_doe', 'password' => 'securepassword123'],
        ['username' => 'jane_smith', 'password' => 'anothersecurepass'],
        ['username' => 'admin', 'password' => 'supersecret'] // For demonstration
    ];

    foreach ($users as $user) {
        if ($user['username'] === $username && password_verify($password, $user['password'])) {
            // Login successful
            session_start(); // Start a session to store user information
            $_SESSION['username'] = $username;
            return true; // Return true to indicate success
        }
    }

    // Login failed
    return false;
}

// **Example Usage (in an HTML form)**
//
// <form method="post" action="login.php">
//   Username: <input type="text" name="username">
//   Password: <input type="password" name="password">
//   <button type="submit">Login</button>
// </form>
//
// **PHP Code to handle the form submission:**
//
// $username = $_POST['username'];
// $password = $_POST['password'];
//
// if (loginUser($username, $password)) {
//   echo "Login successful!  You are now logged in.";
//   // Redirect to a protected page or display a welcome message
//   header("Location: protected_page.php"); // Example: Redirect
//   exit();
// } else {
//   echo "Invalid username or password.";
// }

?>
