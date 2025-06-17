    Username: <input type="text" name="username" placeholder="Your Username" required>
    <br><br>
    Password: <input type="password" name="password" placeholder="Your Password" required>
    <br><br>
    <button type="submit">Login</button>
  </form>

  <br><br>
  <a href="register.php">Don't have an account? Register here.</a>
</body>
</html>


<?php

// Database configuration (replace with your actual credentials)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_db_user";
$db_pass = "your_db_password";

/**
 * Logs in a user based on their username and password.
 *
 * @param string $username The username to authenticate.
 * @param string $password The password to authenticate.
 * @return int|false The user's ID if login is successful, false otherwise.
 */
function loginUser($username, $password)
{
    // Create a database connection
    $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare and execute the query
    $query = "SELECT id FROM users WHERE username = ? AND password = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $username, $password); // "ss" indicates two strings
    $stmt->execute();

    // Get the result
    $result = $stmt->get_result();

    // Check if any rows were returned
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row['id']; // Return the user's ID
    } else {
        return false; // User not found
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
}

// Example Usage (Illustrative - Replace with your actual form submission)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Validate input (Important for security) -  Example Validation
    if (empty($username) || empty($password)) {
        echo "Username and password cannot be empty!";
    } else {
        $userId = loginUser($username, $password);

        if ($userId) {
            echo "Login successful! User ID: " . $userId;
            // Redirect the user or continue with logged-in functionality here
        } else {
            echo "Invalid username or password.";
        }
    }
}

?>
