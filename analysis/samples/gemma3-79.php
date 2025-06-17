

<?php

/**
 * User Login Function
 *
 * This function handles user login using a simple file-based authentication.
 *  **WARNING:** This is a VERY basic example for demonstration purposes ONLY. 
 *  Do NOT use this in a production environment due to security vulnerabilities.
 *  Use a proper database and robust authentication methods.
 *
 * @param string $username The username provided by the user.
 * @param string $password The password provided by the user.
 * @return bool True if login is successful, false otherwise.
 */
function login(string $username, string $password)
{
    // Check if the username and password are provided
    if (empty($username) || empty($password)) {
        return false; // Invalid input
    }

    // Load user data from a file (replace with database access in a real application)
    $user_data = file_get_contents('users.txt');

    if ($user_data === false) {
        return false; // Could not read the file
    }


    // Parse user data (assuming a simple format: username:password)
    $users = explode(',', $user_data);  // Split data if multiple users stored in file

    foreach ($users as $user) {
        $parts = explode(':', $user);
        if (count($parts) == 2) {
            $username_in_file = trim($parts[0]);
            $password_in_file = trim($parts[1]);

            // Compare passwords (case-sensitive)
            if ($username === $username_in_file && $password === $password_in_file) {
                return true; // Login successful
            }
        }
    }

    return false; // Login failed
}


// Example Usage (for demonstration only - NEVER do this in a production environment)
// Create a users.txt file for testing (e.g., username:password)
//   user1:password123
//   user2:secret

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    if (login($username, $password)) {
        echo "Login successful! You are now logged in.";
    } else {
        echo "Login failed.  Incorrect username or password.";
    }
}
?>
