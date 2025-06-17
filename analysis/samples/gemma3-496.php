    Username: <input type="text" name="username">
    Password: <input type="password" name="password">
    <button type="submit">Login</button>
  </form>

</body>
</html>


<?php

// Database Credentials - Replace with your actual values
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_database_username";
$db_password = "your_database_password";

// Function to validate user input
function validate_input($username, $password) {
    // Sanitize inputs (important for security)
    $username = trim($username);
    $password = trim($password);

    // Basic validation - you should enhance this with more checks
    if (empty($username) || empty($password)) {
        return false;
    }

    return true;
}

// Function to authenticate the user
function authenticate_user($username, $password) {
    // Connect to the database
    $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare the SQL query
    $sql = "SELECT * FROM users WHERE username = ? AND password = ?;";

    // Prepare the statement
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        // Bind parameters
        $stmt->bind_param("ss", $username, $password);

        // Execute the statement
        if ($stmt->execute()) {
            // Get the result
            $result = $stmt->get_result();

            if ($result->num_rows == 1) {
                // User found - return user data
                $row = $result->fetch_assoc();
                $stmt->close();  // Close statement
                return $row;
            } else {
                // No user found or multiple users with the same username (shouldn't happen)
                $stmt->close();
                return null;
            }
        } else {
            // Error executing the query
            $stmt->close();
            return null;
        }
    } else {
        // Error preparing the statement
        return null;
    }
}

// Example usage:

// 1. Get user input
$username = $_POST["username"];
$password = $_POST["password"];

// 2. Validate the input
if (!validate_input($username, $password)) {
    echo "Invalid username or password.";
} else {
    // 3. Authenticate the user
    $user = authenticate_user($username, $password);

    // 4. Handle the result
    if ($user) {
        // User is authenticated
        echo "Welcome, " . $user["username"];  // Or use $user["username"]
        // Session management or other authentication-related tasks would go here
    } else {
        // User is not authenticated
        echo "Invalid username or password.";
    }
}

?>
