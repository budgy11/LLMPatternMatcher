    Username: <input type="text" name="username" required><br><br>
    Password: <input type="password" name="password" required><br><br>
    <button type="submit">Login</button>
  </form>

  <p>Don't have an account? <a href="register.php">Register</a></p>

</body>
</html>


<?php

// Database credentials (replace with your actual credentials)
$db_host = "localhost";
$db_user = "your_username";
$db_password = "your_password";
$db_name = "your_database_name";

/**
 * Handles user login.
 *
 * @param string $username The username provided by the user.
 * @param string $password The password provided by the user.
 * @return bool True if login is successful, false otherwise.
 */
function loginUser(string $username, string $password) {
    // Connect to the database
    $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare the SQL query
    $sql = "SELECT * FROM users WHERE username = ? AND password = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        die("Error preparing statement: " . $conn->error);
    }

    // Bind parameters (important for security - prevents SQL injection)
    $stmt->bind_param("ss", $username, $password);

    // Execute the query
    if (!$stmt->execute()) {
        die("Query failed: " . $stmt->error);
    }

    // Get the result
    $result = $stmt->get_result();

    // Check if any rows were returned
    if ($result->num_rows > 0) {
        // User found, you can now work with the user data
        $user = $result->fetch_assoc();
        //  (optional)  Here you would typically store the user ID or session information

        // Example:  Save user ID to a session variable (recommended)
        session_start();
        $_SESSION['user_id'] = $user['id'];  // Assuming 'id' is the primary key in your 'users' table
        // You can also store other user data if needed.
        $stmt->close();
        return true;
    } else {
        // User not found
        $stmt->close();
        return false;
    }
}


// Example usage (in a form):
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    if (loginUser($username, $password)) {
        echo "Login successful! You are now logged in.";
        // Redirect to a protected page or display a welcome message.
        header("Location: /protected_page.php"); // Replace with your desired URL
        exit();
    } else {
        echo "Invalid username or password.";
    }
}
?>
