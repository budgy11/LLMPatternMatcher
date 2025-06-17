
<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
</head>
<body>

    <h1>Login</h1>

    <form method="post" action="">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required><br><br>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br><br>

        <button type="submit">Login</button>
    </form>

</body>
</html>


<?php
session_start(); // Start the session to track user login status

/**
 * Handles user login functionality.
 *
 * @param string $username The user's username.
 * @param string $password The user's password.
 * @return bool True if login is successful, false otherwise.
 */
function loginUser($username, $password)
{
    // Database connection details (Replace with your actual credentials)
    $dbHost = "localhost";
    $dbUsername = "your_db_username";
    $dbPassword = "your_db_password";
    $dbName = "your_db_name";

    // Check if credentials are valid
    $conn = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // SQL query to retrieve user information
    $sql = "SELECT id, username, password FROM users WHERE username = '$username'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        // Verify password
        if (password_verify($password, $row['password'])) {
            // Successful login
            $_SESSION['username'] = $username;  // Store username in session
            $_SESSION['user_id'] = $row['id']; // Also store user ID (recommended)
            return true;
        } else {
            return false; // Incorrect password
        }
    } else {
        return false; // User not found
    }

    $conn->close();
}

/**
 * Handles user logout functionality.
 */
function logoutUser() {
    unset($_SESSION['username']);
    unset($_SESSION['user_id']);
    session_destroy();
}

// Example Usage (Demonstration)

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get username and password from the form
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Call the loginUser function
    if (loginUser($username, $password)) {
        // Login successful
        echo "Login successful! You are now logged in.";
        // Redirect to a protected page, or display a welcome message.
        // For example:
        // header("Location: protected_page.php");
        // exit;
    } else {
        echo "Invalid username or password.";
    }
}

?>
