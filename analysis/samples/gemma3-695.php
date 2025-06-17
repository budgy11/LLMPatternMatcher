    Username: <input type="text" name="username" required>
    Password: <input type="password" name="password" required>
    <button type="submit">Login</button>
  </form>

  <p>Don't have an account? <a href="register.php">Register</a></p>  <!-- Link to registration page -->

</body>
</html>


<?php

// Database configuration (replace with your actual credentials)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_db_user";
$db_password = "your_db_password";

/**
 * Handles user login.
 *
 * @param string $username The username entered by the user.
 * @param string $password The password entered by the user.
 * @return int|false 1 on success, 0 on error, false on failure.
 */
function loginUser($username, $password)
{
    // 1. Connect to the database
    $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

    // Check connection
    if ($conn->connect_error) {
        // Handle connection error
        error_log("Connection failed: " . $conn->connect_error); // Log for debugging
        return 0; // Indicate an error
    }

    // 2. Prepare and execute the SQL query
    $query = "SELECT id, username, password FROM users WHERE username = ?";
    $stmt = $conn->prepare($query);

    if ($stmt === false) {
        // Handle query preparation error
        error_log("Query preparation failed: " . $conn->error);
        $conn->close();
        return 0;
    }

    $stmt->bind_param("s", $username); // 's' indicates a string parameter

    if (!$stmt->execute()) {
        // Handle execution error
        error_log("Query execution failed: " . $stmt->error);
        $stmt->close();
        $conn->close();
        return 0;
    }

    // 3. Get the result
    $result = $stmt->get_result();

    // 4. Check if a user was found
    if ($result->num_rows == 0) {
        $stmt->close();
        $conn->close();
        return false; // User not found
    }

    $user = $result->fetch_assoc(); // Fetch the user data as an associative array
    $stmt->close();
    $conn->close();

    // 5. Verify the password
    if (password_verify($password, $user['password'])) {
        return 1; // Login successful
    } else {
        return false; // Incorrect password
    }
}



// Example usage:  (This part is just for demonstration and testing)
//  This will not be executed in a typical web application

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    if (loginUser($username, $password)) {
        echo "Login successful!  You are now logged in.";
        // Redirect to a protected page or display a welcome message
    } else {
        echo "Invalid username or password.";
    }
}
?>
