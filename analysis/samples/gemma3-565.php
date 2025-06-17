
<!DOCTYPE html>
<html>
<head>
  <title>User Login</title>
</head>
<body>

  <h2>Login</h2>

  <form method="post" action="">
    Username: <input type="text" name="username" required><br><br>
    Password: <input type="password" name="password" required><br><br>
    <input type="submit" value="Login">
  </form>

</body>
</html>


<?php
session_start(); // Start the session

/**
 * Logs a user in.
 *
 * @param string $username The username of the user.
 * @param string $password The password of the user.
 * @return bool True on success, false on failure.
 */
function login(string $username, string $password)
{
    // Validate inputs (simple example - improve for production)
    if (empty($username) || empty($password)) {
        return false;
    }

    // Check credentials against a "database" (replace with actual database query)
    $validUsers = [
        'john.doe' => 'password123',
        'jane.smith' => 'securepass',
    ];

    if (isset($validUsers[$username])) {
        if ($validUsers[$username] === $password) {
            // Authentication successful
            $_SESSION['username'] = $username; // Store username in session
            return true;
        } else {
            // Incorrect password
            return false;
        }
    } else {
        // User not found
        return false;
    }
}

/**
 * Logs a user out.
 */
function logout() {
    unset($_SESSION['username']); // Remove username from session
    session_destroy(); // Destroy the session
}

// --- Example Usage (demonstrates login and logout) ---

// 1. Check if the user is already logged in
if (isset($_SESSION['username'])) {
    echo "You are already logged in as " . htmlspecialchars($_SESSION['username']) . "<br>";
}

// 2. Check for login form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = htmlspecialchars($_POST["username"]);
    $password = htmlspecialchars($_POST["password"]);

    // Call the login function
    if (login($username, $password)) {
        echo "Login successful! You are now logged in as " . htmlspecialchars($_SESSION['username']) . "<br>";
    } else {
        echo "Invalid username or password. Please try again.<br>";
    }
}
?>
