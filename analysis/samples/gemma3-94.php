    Username: <input type="text" name="username" value="<?php echo isset($username) ? htmlspecialchars($username) : ''; ?>">
    Password: <input type="password" name="password">
    <button type="submit">Login</button>
  </form>

  <p>Don't have an account? <a href="register.php">Register here</a></p>

</body>
</html>


<?php
session_start(); // Start the session at the beginning of your script

/**
 * User Login Function
 *
 * @param string $username The username to log in.
 * @param string $password The password for the user.
 * @return bool True if login is successful, false otherwise.
 */
function loginUser($username, $password)
{
    // Database connection details - Replace with your actual credentials
    $db_host = 'localhost';
    $db_username = 'your_db_username';
    $db_password = 'your_db_password';
    $db_name = 'your_database_name';

    // Check if the username and password are provided
    if (empty($username) || empty($password)) {
        return false;
    }

    // Connect to the database
    $conn = new mysqli($db_host, $db_username, $db_password, $db_name);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // SQL query to retrieve the password hash for the given username
    $sql = "SELECT password_hash, user_id FROM users WHERE username = '$username'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $hashed_password = $row['password_hash'];
        $userId = $row['user_id']; // Store user ID for potential later use

        // Verify password using password_verify()
        if (password_verify($password, $hashed_password)) {
            // Password matches!  Set session variables
            $_SESSION['user_id'] = $userId;  // Store user ID in session
            $_SESSION['username'] = $username; // Store username for convenience
            return true; // Login successful
        } else {
            return false; // Password does not match
        }
    } else {
        return false; // User not found
    }

    $conn->close(); // Close the database connection
}


// Example Usage (Demonstration - This part would be in your form submission handler)
// Assume a form has been submitted with username and password

// if (isset($_POST['username']) && isset($_POST['password'])) {
//     $username = $_POST['username'];
//     $password = $_POST['password'];
//
//     if (loginUser($username, $password)) {
//         // Login successful - Redirect to a protected page or display a welcome message
//         header("Location: /protected_page.php"); // Replace with your desired redirect
//         exit();
//     } else {
//         // Login failed - Display an error message
//         echo "<p style='color:red;'>Invalid username or password.</p>";
//     }
// }
?>
