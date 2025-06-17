    <br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" placeholder="Enter your password">
    <br><br>

    <button type="submit">Login</button>
  </form>

  <br>
  <a href="register.php">Don't have an account? Register here.</a>

</body>
</html>


<?php
session_start();

/**
 * Handles user login functionality.
 *
 * @param string $username The username to log in.
 * @param string $password The password for the username.
 * @return bool True if login is successful, false otherwise.
 */
function loginUser(string $username, string $password)
{
    // **IMPORTANT:  Never store passwords directly in your code!**
    // This is a simplified example for demonstration purposes.
    // In a real-world application, you MUST hash the password 
    // before storing it in the database.

    // Simulated Database (replace with your actual database connection)
    $validUsers = [
        'john.doe' => 'password123',
        'jane.smith' => 'securePass',
    ];

    // Convert input to lowercase for case-insensitive comparison
    $username = strtolower($username);

    if (isset($validUsers[$username])) {
        // Verify the password
        if ($validUsers[$username] === $password) {
            // Successful login!
            $_SESSION['user_id'] = $username; // Store user ID (or any relevant user data)
            $_SESSION['logged_in'] = true;
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


// Example usage:  (This code would typically be in a form submission handler)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    if (loginUser($username, $password)) {
        // Login successful, redirect to a secure page
        header("Location: /secure_page.php"); // Replace with your secure page URL
        exit();
    } else {
        // Login failed
        $error = "Invalid username or password.";
        echo "<p style='color: red;'>$error</p>";
    }
}
?>
