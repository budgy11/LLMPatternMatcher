        Username: <input type="text" name="username" placeholder="Enter your username"><br><br>
        Password: <input type="password" name="password" placeholder="Enter your password"><br><br>
        <input type="submit" value="Login">
    </form>

    <p>Don't have an account? <a href="register.php">Register</a></p>

</body>
</html>


<?php
session_start();

// Database credentials (replace with your actual credentials)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_database_user";
$db_password = "your_database_password";

/**
 * Logs in a user.
 *
 * @param string $username The username to log in.
 * @param string $password The password.
 * @return bool True if login successful, false otherwise.
 */
function loginUser($username, $password)
{
    // Connect to the database
    $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // SQL query to check the credentials
    $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // User found, set session variables
        $user = $result->fetch_assoc();
        $_SESSION['username'] = $user['username'];
        $_SESSION['user_id'] = $user['id']; // Assuming you have a user ID
        return true;
    } else {
        return false;
    }

    $conn->close();
}

/**
 * Logs out the current user.
 */
function logoutUser() {
    // Destroy the session
    session_unset();
    session_destroy();
}

// Example usage:

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get username and password from the form
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Validate input (important for security - see explanation below)
    if (empty($username) || empty($password)) {
        $error = "Username and password cannot be empty.";
    } else {
        // Attempt to log in
        if (loginUser($username, $password)) {
            // Login successful, redirect to a different page
            header("Location: user_dashboard.php"); // Redirect to a secure page
            exit();
        } else {
            $error = "Invalid username or password.";
        }
    }
}
?>
