    Username: <input type="text" name="username" required>
    Password: <input type="password" name="password" required>
    <button type="submit">Login</button>
  </form>

  <p>Don't have an account? <a href="register.php">Register</a></p>
</body>
</html>


<?php
session_start();

/**
 * User login function.
 *
 * @param string $username The user's username.
 * @param string $password The user's password.
 * @return bool True if login is successful, false otherwise.
 */
function loginUser(string $username, string $password)
{
    // Database credentials (replace with your actual credentials)
    $db_host = 'localhost';
    $db_username = 'your_db_username';
    $db_password = 'your_db_password';
    $db_name = 'your_db_name';

    // Connect to the database
    $conn = new mysqli($db_host, $db_username, $db_password, $db_name);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare the SQL query
    $sql = "SELECT id, username, password FROM users WHERE username = ?";

    // Prepare the statement
    $stmt = $conn->prepare($sql);

    // Bind the parameter
    $stmt->bind_param("s", $username);

    // Execute the statement
    if ($stmt->execute() === TRUE) {
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();
            $hashed_password = $row['password']; // Get the hashed password

            // Verify the password
            if (password_verify($password, $hashed_password)) {
                // Login successful
                $_SESSION['username'] = $username;
                $_SESSION['id'] = $row['id']; // Store the user ID
                return true;
            } else {
                // Password mismatch
                return false;
            }
        } else {
            // No user found
            return false;
        }
    } else {
        // Error executing the query
        return false;
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
}

// Example Usage (for demonstration only - don't expose this in a real application)
// $username = "testuser";
// $password = "password123";

// if (loginUser($username, $password)) {
//     echo "Login successful!";
// } else {
//     echo "Login failed.";
// }

?>
