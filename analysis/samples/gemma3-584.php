

<?php

// Database credentials - Replace with your actual values
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_db_user";
$db_password = "your_db_password";

/**
 * Logs in a user.
 *
 * @param string $username The username to log in.
 * @param string $password The password for the user.
 * @return int|string  Returns the user's ID if login is successful, 
 *                      or an error message string if login fails.
 */
function loginUser(string $username, string $password)
{
    // 1. Connect to the database
    $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

    // Check connection
    if ($conn->connect_error) {
        return "Connection failed: " . $conn->connect_error;
    }

    // 2. Prepare the SQL query
    $sql = "SELECT id, username FROM users WHERE username = ? AND password = ?";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        return "Error preparing statement.";
    }

    // 3. Bind parameters
    $stmt->bind_param("ss", $username, $password); // "ss" indicates two string parameters

    // 4. Execute the query
    if (!$stmt->execute()) {
        return "Error executing query: " . $stmt->error;
    }

    // 5. Get the result
    $result = $stmt->get_result();

    // 6. Check if a user was found
    if ($result->num_rows == 0) {
        return "Invalid username or password.";
    }

    $user = $result->fetch_assoc(); // Fetch the user data as an associative array
    $user_id = $user['id'];

    // 7. Close the statement and connection
    $stmt->close();
    $conn->close();

    return $user_id; // Return the user ID
}

// Example usage:
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    $loginResult = loginUser($username, $password);

    if (strpos($loginResult, "Invalid") !== false) {
        echo "<p style='color:red;'>" . $loginResult . "</p>";
    } else {
        // Successful login -  You can redirect the user or display their profile
        echo "<p style='color:green;'>Login successful!  User ID: " . $loginResult . "</p>";
        // Redirect to a different page or display user data
    }
}
?>
