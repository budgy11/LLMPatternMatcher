
<!DOCTYPE html>
<html>
<head>
  <title>Login Form</title>
</head>
<body>

  <h1>Login</h1>

  <form method="post" action="">
    Username: <input type="text" name="username" required><br><br>
    Password: <input type="password" name="password" required><br><br>
    <input type="submit" value="Login">
  </form>

</body>
</html>


<?php
session_start();

// Database credentials (Replace with your actual credentials)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_db_user";
$db_pass = "your_db_password";

/**
 * User Login Function
 *
 * This function handles user login using credentials from form input.
 *
 * @param string $username The user's username.
 * @param string $password The user's password.
 * @return bool True if login is successful, false otherwise.
 */
function loginUser($username, $password)
{
    // Connect to the database
    $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // SQL query to authenticate the user
    $sql = "SELECT id, username, password FROM users WHERE username = '$username'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        // Compare passwords (Use password_hash for secure password storage in production)
        if (password_verify($password, $row['password'])) {
            // Login successful
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['username'] = $username;
            return true;
        } else {
            // Incorrect password
            return false;
        }
    } else {
        // User not found
        return false;
    }

    // Close the connection
    $conn->close();
}

/**
 * Logout Function
 */
function logoutUser() {
    unset($_SESSION['user_id']);
    unset($_SESSION['username']);
    session_destroy();
}


// --- Example Usage (Handle Login Form Submission) ---

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    if (loginUser($username, $password)) {
        // Redirect to a protected page or display a success message
        header("Location: /protected_page.php"); // Replace with your protected page URL
        exit(); // Important: Stop further script execution after redirect
    } else {
        // Display error message
        $error = "Invalid username or password.";
    }
}

?>
