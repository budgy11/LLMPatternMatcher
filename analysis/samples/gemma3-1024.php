    Username: <input type="text" name="username" required>
    Password: <input type="password" name="password" required>
    <button type="submit">Login</button>
</form>

</body>
</html>


<?php

/**
 * User Login Function
 *
 * This function handles user login with basic authentication.
 *
 * @param string $username The username provided by the user.
 * @param string $password The password provided by the user.
 * @return int|false  Returns the user ID if login is successful, 
 *                    false if login fails.
 */
function loginUser(string $username, string $password) {
    // ** IMPORTANT SECURITY NOTE:  This is a VERY basic example.
    //   In a real-world application, NEVER store passwords in plain text.
    //   Always use a strong hashing algorithm like bcrypt or Argon2.
    //   This example is for demonstration purposes ONLY.
    
    //  Check if the username and password match our (fake) stored credentials.
    //  In a real application, this would involve querying a database.
    
    //  This uses a simple, insecure dictionary-like approach for demonstration only.
    $credentials = [
        'john_doe' => 'secret_password123',
        'jane_smith' => 'another_secret',
    ];

    if (isset($credentials[$username])) {
        $storedPassword = $credentials[$username];

        // Basic password verification (DO NOT USE IN PRODUCTION)
        if (password_verify($password, $storedPassword)) {
            // Login successful - Get the user ID (for demonstration)
            return $username;  // Or, you would typically return the user ID from your database
        } else {
            // Password mismatch
            return false;
        }
    } else {
        // Username not found
        return false;
    }
}

// ** Example Usage (for testing) **
// Simulate a login form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    if (loginUser($username, $password)) {
        echo "Login successful!  Welcome, " . $username . "!";
    } else {
        echo "Invalid username or password.";
    }
}
?>
