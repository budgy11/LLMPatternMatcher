    Username: <input type="text" name="username" required>
    Password: <input type="password" name="password" required>
    <button type="submit">Login</button>
  </form>

</body>
</html>


// ... (rest of the code)

  if (empty($username) || empty($password)) {
    $error = "Username and password must be filled.";
  } elseif (!filter_var($username, FILTER_VALIDATE_EMAIL)) {
    $error = "Invalid username (Please enter a valid email address).";
  } else {
    // ... (database connection) ...

    $password = password_hash($_POST["password"], PASSWORD_DEFAULT); // Hash the password
    $sql = "SELECT id, username, password FROM users WHERE username = ? AND password = ?";
    ...
    $stmt->bind_param("ss", $username, $password); // "ss" indicates two string parameters
    $stmt->execute();
    ...
  }


<?php
session_start(); // Start the session

/**
 * Handles user login.
 *
 * @param string $username The user's username.
 * @param string $password The user's password.
 * @return bool True if login is successful, false otherwise.
 */
function loginUser($username, $password) {
    // Database connection (Replace with your actual database credentials)
    $db_host = "localhost";
    $db_name = "your_database_name";
    $db_user = "your_database_user";
    $db_password = "your_database_password";

    // Connect to the database
    $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Sanitize inputs - IMPORTANT for security
    $username = trim($username);
    $password = trim($password);

    // SQL query
    $sql = "SELECT id, username, password FROM users WHERE username = '$username'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Get data from the result
        $user_data = $result->fetch_assoc();

        // Verify password
        if (password_verify($password, $user_data['password'])) {
            // Login successful
            $_SESSION['user_id'] = $user_data['id'];
            $_SESSION['username'] = $user_data['username'];

            return true;
        } else {
            return false; // Password incorrect
        }
    } else {
        return false; // User not found
    }

    // Close the connection
    $conn->close();
}


// Example Usage:  (This is for demonstrating the function, not for a complete form)
/*
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    if (loginUser($username, $password)) {
        echo "Login successful!  Redirecting...";
        header("Location: user_dashboard.php"); // Redirect to a secure page
        exit();
    } else {
        echo "Invalid username or password.";
    }
}
*/

?>
