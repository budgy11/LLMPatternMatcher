

<?php

// Database configuration (replace with your actual credentials)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_db_user";
$db_pass = "your_db_password";

/**
 * Logs in a user based on their username and password.
 *
 * @param string $username The username.
 * @param string $password The password.
 * @return int|false The user ID if login is successful, or false if login fails.
 */
function loginUser(string $username, string $password) {
    // Connect to the database
    $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare the SQL query
    $sql = "SELECT id, username FROM users WHERE username = ? AND password = ?";

    // Prepare the statement
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        die("Error preparing statement: " . $conn->error);
    }

    // Bind parameters
    $stmt->bind_param("ss", $username, $password);  // "ss" indicates two string parameters

    // Execute the statement
    if (!$stmt->execute()) {
        $errorInfo = $stmt->errorInfo();
        die("Error executing query: " . $errorInfo[2]);
    }

    // Get the result
    $result = $stmt->get_result();

    // Check if any rows were returned
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $userId = $row['id'];
        return $userId;
    } else {
        return false;
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
}

// Example usage:
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    if (empty($username) || empty($password)) {
        echo "Username and password cannot be empty.";
    } else {
        $userId = loginUser($username, $password);

        if ($userId) {
            echo "Login successful! User ID: " . $userId;
            // You can redirect the user to a different page or perform other actions here.
            // Example: header("Location: welcome.php?userId=" . $userId); // Redirect to a welcome page
        } else {
            echo "Invalid username or password.";
        }
    }
}
?>
