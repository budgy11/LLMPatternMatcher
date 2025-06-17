    Username: <input type="text" name="username">
    <br>
    Password: <input type="password" name="password">
    <br>
    <button type="submit">Login</button>
</form>

</body>
</html>


<?php

/**
 * Handles user login functionality.
 *
 * This function validates user credentials against a data source (e.g., database).
 *
 * @param string $username The user's username.
 * @param string $password The user's password.
 * @return int|null  The user's ID if login is successful, or null if not.
 */
function loginUser(string $username, string $password)
{
    // **IMPORTANT SECURITY NOTE:**  Do NOT store passwords in plain text.
    // This example uses a simple hash for demonstration purposes ONLY.
    // In a real application, you MUST use a strong password hashing algorithm
    // like bcrypt or Argon2.
    $hashedPasswords = [
        'john.doe' => '$2y$10$abcdefg1234567890abcdefg1234567890', // Example hash
        'jane.smith' => '$2y$10$hijklmn0123456789hijklmn0123456789', // Example hash
    ];

    // Normalize username (lowercase and remove spaces)
    $username = strtolower(trim($username));

    if (isset($hashedPasswords[$username])) {
        // Verify the password
        if (password_verify($password, $hashedPasswords[$username])) {
            // Login successful - return the user's ID
            return array_key_first($hashedPasswords, $username); // Returns the first key that matches the username
        } else {
            // Incorrect password
            return null;
        }
    } else {
        // User not found
        return null;
    }
}

// **Example Usage:**

// Simulate form submission (replace with your actual form handling)
$username = $_POST['username'];
$password = $_POST['password'];

$userId = loginUser($username, $password);

if ($userId) {
    echo "Login successful! User ID: " . $userId . "<br>";
    // Redirect the user to a secure area
    // header("Location: /secure_area.php?id=" . $userId);
    // exit();

} else {
    echo "Login failed. Please check your credentials.<br>";
}


?>
