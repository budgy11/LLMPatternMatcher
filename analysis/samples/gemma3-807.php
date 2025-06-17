
</body>
</html>


<?php

// Database credentials (replace with your actual details)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_database_username";
$db_password = "your_database_password";

/**
 * Logs in a user based on username and password.
 *
 * @param string $username The username to log in with.
 * @param string $password The password to log in with.
 * @return int|false The user ID if login is successful, false otherwise.
 */
function loginUser($username, $password)
{
    // Connect to the database
    $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

    // Check the connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare the SQL query
    $query = "SELECT id FROM users WHERE username = ? AND password = ? ";
    $stmt = $conn->prepare($query);

    if ($stmt === false) {
        die("Error preparing statement: " . $conn->error);
    }

    // Bind the parameters
    $stmt->bind_param("ss", $username, $password);

    // Execute the query
    if (!$stmt->execute()) {
        die("Query failed: " . $stmt->error);
    }

    // Get the result
    $result = $stmt->get_result();

    // Check if a user was found
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $userId = $user['id'];
        $stmt->close();  // Close the statement
        $conn->close(); // Close the connection
        return $userId;
    } else {
        $stmt->close();
        $conn->close();
        return false;
    }
}

// Example Usage:
// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Validate input (basic example, you should implement more robust validation)
    if (empty($username) || empty($password)) {
        $error = "Username and password cannot be empty.";
    } else {
        $userId = loginUser($username, $password);
        if ($userId) {
            echo "Login successful! User ID: " . $userId;
            // Redirect to a secure page or set a session variable
            // For example:
            // session_start();
            // $_SESSION['user_id'] = $userId;
            // header("Location: secure_page.php");
            // exit();
        } else {
            $error = "Invalid username or password.";
        }
    }
}
?>
