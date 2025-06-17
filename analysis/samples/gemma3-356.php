    <label for="username">Username:</label>
    <input type="text" id="username" name="username" placeholder="Your Username">
    <br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" placeholder="Your Password">
    <br><br>

    <button type="submit">Login</button>
  </form>

  <br><br>
  <a href="register.php">Don't have an account? Register here</a>

</body>
</html>


<?php

// Database credentials (Replace with your actual credentials)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_db_user";
$db_password = "your_db_password";

// Function to handle user login
function loginUser($username, $password) {
    // Connect to the database
    $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // SQL query to check username and password
    $sql = "SELECT id, username, password FROM users WHERE username = '$username'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        // Verify password (Important:  Never store passwords directly.  Use hashing!)
        if (password_verify($password, $row['password'])) {
            // Login successful!  Set session variables
            session_start();
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['username'] = $username;

            return true; // Login successful
        } else {
            return false; // Incorrect password
        }
    } else {
        return false; // User not found
    }
}

// Example usage (in a form or similar)

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Validate input (important for security)
    if (empty($username) || empty($password)) {
        echo "Username and password cannot be empty.";
    } else {
        // Call the loginUser function
        if (loginUser($username, $password)) {
            // Redirect to a secure page or display a welcome message
            header("Location: secure_page.php"); // Replace with your secure page
            exit();
        } else {
            echo "Invalid username or password.";
        }
    }
}
?>
