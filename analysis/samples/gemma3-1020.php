        <label for="username">Username:</label>
        <input type="text" id="username" name="username">

        <label for="password">Password:</label>
        <input type="password" id="password" name="password">

        <button type="submit">Login</button>
    </form>

</body>
</html>


<?php

// Database credentials (Replace with your actual values)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_database_user";
$db_password = "your_database_password";

// Function to handle user login
function loginUser($username, $password)
{
    // Connect to the database
    $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // SQL query to retrieve user data
    $sql = "SELECT id, username, password FROM users WHERE username = '$username'";  // Adjust column names if different
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        // Verify password
        if (password_verify($password, $row['password'])) {
            // Successful login
            session_start();
            // Store user information in session (important for tracking user)
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['username'] = $row['username'];
            return true; // Login successful
        } else {
            return false; // Password incorrect
        }
    } else {
        return false; // User not found
    }

    $conn->close();
}

// Example usage (handling the login form)

// Check if the login form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    if (loginUser($username, $password)) {
        // Redirect to a protected page or display a success message
        header("Location: protected_page.php"); // Replace with your protected page URL
        exit();
    } else {
        $error = "Invalid username or password.";
    }
}
?>
