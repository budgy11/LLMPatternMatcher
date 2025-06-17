

<?php

/**
 * User Login Function
 *
 * This function handles user login.
 *
 * @param string $username The username provided by the user.
 * @param string $password The password provided by the user.
 * @return int|false  The user ID if login is successful, false otherwise.
 */
function loginUser(string $username, string $password) {
    //  Replace this with your actual database connection and user data
    //  This is a simplified example for demonstration purposes.

    $validUsers = [
        ['username' => 'john_doe', 'password' => 'securepassword123'],
        ['username' => 'jane_smith', 'password' => 'anotherpassword'],
    ];

    // Convert inputs to lowercase for case-insensitive comparison
    $username = strtolower($username);
    $password = strtolower($password);

    foreach ($validUsers as $user) {
        if ($user['username'] === $username && $user['password'] === $password) {
            // User found, return the user ID
            return $user['id'];  // Assuming you have an 'id' field in your user data
        }
    }

    // User not found, return false
    return false;
}

// Example Usage (for demonstration - remove in actual application)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    if (empty($username) || empty($password)) {
        echo "<p style='color: red;'>Username and password cannot be empty.</p>";
    } else {
        $userId = loginUser($username, $password);

        if ($userId) {
            echo "<p style='color: green;'>Login successful! User ID: " . $userId . "</p>";
            //  You can redirect the user to a different page here.
        } else {
            echo "<p style='color: red;'>Invalid username or password.</p>";
        }
    }
}
?>
