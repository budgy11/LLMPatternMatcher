    Username: <input type="text" name="username" required>
    Password: <input type="password" name="password" required>
    <button type="submit">Login</button>
  </form>

</body>
</html>


<?php

/**
 * Handles user login functionality.
 *
 * @param string $username The user's username.
 * @param string $password The user's password.
 * @return int|false 1 if successful, false if failed.
 */
function loginUser(string $username, string $password)
{
    // **IMPORTANT:**  Replace this with a secure database connection and query.
    // This example uses a simple, insecure, in-memory array for demonstration purposes only.
    // NEVER use this in a production environment!

    $users = [
        ['username' => 'john.doe', 'password' => 'securePassword123'],
        ['username' => 'jane.smith', 'password' => 'anotherPassword'],
        ['username' => 'admin', 'password' => 'superSecretPass']
    ];

    // Find the user in the array
    foreach ($users as $user) {
        if ($user['username'] === $username && password_verify($password, $user['password'])) {
            // Authentication successful!  Store a session ID or similar to track the user.
            session_start();
            $_SESSION['username'] = $username; // Store username in session
            return 1; // Indicate success
        }
    }

    // Authentication failed
    return false;
}

// Example usage (from a login form):
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (loginUser($username, $password)) {
        // Successful login - Redirect to a secure page or display a welcome message.
        header("Location: /welcome.php"); // Replace /welcome.php with your desired page
        exit();
    } else {
        // Login failed - Display an error message.
        echo "<p style='color:red;'>Invalid username or password.</p>";
    }
}

// Example form (HTML - place this in your login form)
?>
