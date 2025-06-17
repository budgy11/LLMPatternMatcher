    Username: <input type="text" name="username" required><br><br>
    Password: <input type="password" name="password" required><br><br>
    <input type="submit" value="Login">
  </form>

</body>
</html>


<?php

// Database credentials (replace with your actual values)
$db_host = "localhost";
$db_username = "your_username";
$db_password = "your_password";
$db_name = "your_database";

// Function to handle user login
function loginUser($username, $password) {
    // Connect to the database
    $conn = new mysqli($db_host, $db_username, $db_password, $db_name);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare the SQL query
    $sql = "SELECT id, username, password FROM users WHERE username = ? AND password = ?;";

    // Bind the parameters (important for security - prevents SQL injection)
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $username, $password);  // "ss" means two string parameters

    // Execute the query
    if ($stmt->execute() === TRUE) {
        // Get the result
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            // User found
            $row = $result->fetch_assoc();
            // Set session variables
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['username'] = $row['username'];

            return true; // Login successful
        } else {
            return false; // User not found
        }
    } else {
        return false; // Query execution failed
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
}

// Example usage (simplified - for demonstration only)
// In a real application, this would come from a form submission.
// For demonstration, we'll try to login with some example credentials
if (isset($_POST['username']) && isset($_POST['password'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (loginUser($username, $password)) {
        echo "Login successful!  You are now logged in as " . $_SESSION['username'];
    } else {
        echo "Invalid username or password.";
    }
}
?>
