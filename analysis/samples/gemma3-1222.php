    Username: <input type="text" name="username" required>
    Password: <input type="password" name="password" required>
    <button type="submit">Login</button>
  </form>

  <br>
  <a href="register.php">Don't have an account? Register here.</a>

</body>
</html>


<?php

/**
 * User Login Function
 *
 * This function handles user login validation and returns user data if successful,
 * or an error message if login fails.
 *
 * @param string $username  The username to log in.
 * @param string $password The password to log in.
 * @param array $users     An associative array of usernames and their passwords.
 *                        Example: ['john.doe' => 'password123', 'jane.smith' => 'securePass']
 *
 * @return array|string  An array containing user data (id, username, etc.) on success,
 *                       or an error message string on failure.
 */
function login(string $username, string $password, array $users) {
    // Sanitize input -  Crucial for security!
    $username = trim($username);
    $password = trim($password);

    // Check if the user exists
    if (isset($users[$username])) {
        // Verify the password
        if (password_verify($password, $users[$username])) {  // Uses password_verify
            // User found and password matches - Create user data array
            $userData = [
                'id' => $username, // Using username as ID - can change to a real ID field
                'username' => $username,
            ];

            return $userData;
        } else {
            return "Incorrect password for " . $username;
        }
    } else {
        return "User " . $username . " not found";
    }
}

// Example Usage (for testing - REMOVE OR COMMENT OUT IN A REAL APPLICATION)
//  In a real application, you'd get these values from a form submission.

// Sample user data (in a real application, this would come from a database)
$users = [
    'john.doe' => '$2y$11$K3c2V1o8tG0wS16J8Xy1Z.w17xQ7fJ5p77789Qk9fV33333', // Hashed password
    'jane.smith' => '$2y$11$rGj2H9uL9oT6z953zY2c7.u41xQ7fJ5p77789Qk9fV33333', // Another hashed password
];


// Test login attempts
$loginResult1 = login('john.doe', 'password123', $users);
if ($loginResult1 === 'Incorrect password for john.doe') {
    echo "Login Failed: Incorrect password.
";
} elseif ($loginResult1 !== null) {
    echo "Login Successful!
";
    print_r($loginResult1);
} else {
    echo "Login Failed: User not found.
";
}

$loginResult2 = login('nonexistent.user', 'anypassword', $users);
if ($loginResult2 === 'Incorrect password for nonexistent.user') {
    echo "Login Failed: Incorrect password.
";
} elseif ($loginResult2 !== null) {
    echo "Login Successful!
";
    print_r($loginResult2);
} else {
    echo "Login Failed: User not found.
";
}

$loginResult3 = login('john.doe', 'wrongpassword', $users);
if ($loginResult3 === 'Incorrect password for john.doe') {
    echo "Login Failed: Incorrect password.
";
} elseif ($loginResult3 !== null) {
    echo "Login Successful!
";
    print_r($loginResult3);
} else {
    echo "Login Failed: User not found.
";
}
?>
