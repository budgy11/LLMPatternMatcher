    Username: <input type="text" name="username" required><br><br>
    Password: <input type="password" name="password" required><br><br>
    <input type="submit" value="Login">
  </form>

</body>
</html>


<?php

/**
 * User Login Function
 *
 * This function handles user login authentication.
 *
 * @param string $username The username provided by the user.
 * @param string $password The password provided by the user.
 * @return int|false $user_id if login successful, false otherwise.
 */
function loginUser(string $username, string $password) {
    // **IMPORTANT: In a real application, you would NEVER store passwords directly in the database.**
    // This is a simplified example for demonstration purposes only.
    // Use password hashing functions like password_hash() and password_verify() for security.

    // Simulate a simple database (replace with your actual database connection)
    $users = [
        ['username' => 'john.doe', 'password' => 'securepassword123'],
        ['username' => 'jane.smith', 'password' => 'anotherpassword'],
        ['username' => 'admin', 'password' => 'adminpass'],
    ];

    // Loop through the user data and compare the provided username and password
    foreach ($users as $user) {
        if ($user['username'] === $username && password_verify($password, $user['password'])) { // Use password_verify!
            // User found and password matches!
            return $user['id']; // Assuming you have an 'id' field in your users
        }
    }

    // User not found or password does not match
    return false;
}



/**
 *  Example Usage (for demonstration only - do not use in production without proper security)
 */

// Get username and password from form input (in a real application, this would come from a form)
$username = $_POST['username'];
$password = $_POST['password'];

// Check if the login form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = loginUser($username, $password);

    if ($user_id !== false) {
        // Login successful!
        echo "Login successful! User ID: " . $user_id;

        // Store the user ID (e.g., in a session, cookie, or database)
        // Example:
        session_start();
        $_SESSION['user_id'] = $user_id;
        // ... other session setup ...
        
    } else {
        // Login failed
        echo "Invalid username or password.";
    }
}
?>
