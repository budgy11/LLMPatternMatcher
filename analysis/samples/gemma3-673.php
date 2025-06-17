

<?php

// Database configuration (Replace with your actual credentials)
$db_host = 'localhost';
$db_name = 'your_database_name';
$db_user = 'your_db_user';
$db_pass = 'your_db_password';

/**
 * Logs in a user based on username and password.
 *
 * @param string $username The user's username.
 * @param string $password The user's password.
 * @return int|false The user ID if successful, false if not.
 */
function loginUser($username, $password) {
    // Connect to the database
    $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

    // Check the connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare the SQL query
    $query = "SELECT id FROM users WHERE username = ? AND password = ? ";  // Use prepared statement to prevent SQL injection

    // Bind parameters
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $username, $password); // "ss" indicates two string parameters

    // Execute the query
    if ($stmt->execute() === TRUE) {
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        if ($row) {
            // User found, return the user ID
            return $row['id'];
        } else {
            // User not found
            $stmt->close();
            return false;
        }
    } else {
        // Query execution failed
        $stmt->close();
        return false;
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
}



// Example usage:
// Get username and password from form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Validate input (Important for security)
    if (empty($username) || empty($password)) {
        echo "Username and password cannot be empty.";
    } else {
        // Call the login function
        $userId = loginUser($username, $password);

        if ($userId) {
            // User logged in successfully
            session_start();
            $_SESSION['userId'] = $userId;  // Store user ID in session
            echo "Login successful!  User ID: " . $userId;
            // Redirect to a protected page or display a welcome message
            header("Location: /protected_page.php"); // Example
            exit();
        } else {
            // Login failed
            echo "Invalid username or password.";
        }
    }
}

?>
