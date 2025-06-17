        <label for="username">Username:</label>
        <input type="text" id="username" name="username">
        <br><br>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password">
        <br><br>
        <button type="submit">Login</button>
    </form>

</body>
</html>


<?php

// Database credentials (replace with your actual values)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_username";
$db_password = "your_password";

// Function to handle user login
function loginUser($username, $password) {
    // Connect to the database
    $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare the SQL query
    $query = "SELECT id, username, password FROM users WHERE username = ? AND password = ?";
    $stmt = $conn->prepare($query);

    if ($stmt === false) {
        die("Error preparing statement: " . $conn->error);
    }

    // Bind parameters to prevent SQL injection
    $stmt->bind_param("ss", $username, $password);

    // Execute the query
    if (!$stmt->execute()) {
        echo "Error executing query: " . $stmt->error;
        return false;
    }

    // Get the result
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        return false; // User not found
    }

    // Fetch the user data
    $user = $result->fetch_assoc();

    // Close the statement and connection
    $stmt->close();
    $conn->close();

    // Set session variables
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['username'] = $user['username'];

    return true; // Login successful
}

// Example Usage (within a form or other user interaction)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    if (loginUser($username, $password)) {
        // Redirect to a secure page or display a welcome message
        header("Location: /welcome.php"); // Replace with your welcome page URL
        exit();
    } else {
        echo "Invalid username or password.";
    }
}

?>
