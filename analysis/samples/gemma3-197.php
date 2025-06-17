    Username: <input type="text" name="username" required><br><br>
    Password: <input type="password" name="password" required><br><br>
    <input type="submit" value="Login">
  </form>

</body>
</html>


<?php

// Database credentials - REPLACE with your actual values
$db_host = 'localhost';
$db_name = 'your_database_name';
$db_user = 'your_db_user';
$db_pass = 'your_db_password';

// Session handling (for keeping users logged in)
session_start();

// Function to validate user credentials
function validate_login($username, $password) {
    // Connect to the database
    $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare and execute the query
    $query = "SELECT * FROM users WHERE username = ? AND password = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $username, $password);  // "ss" indicates two string parameters
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        return $user;
    } else {
        return null;
    }

    $stmt->close();
    $conn->close();
}

// Handle login request
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Validate input (important for security)
    if (empty($username) || empty($password)) {
        $error = "Username and password cannot be empty.";
    } else {
        $user = validate_login($username, $password);

        if ($user) {
            // Login successful
            $_SESSION["user_id"] = $user["id"]; // Store user ID in session
            $_SESSION["username"] = $user["username"];

            // Redirect to a secure page (e.g., dashboard)
            header("Location: dashboard.php"); // Replace with your dashboard URL
            exit(); // Important to stop further execution
        } else {
            $error = "Invalid username or password.";
        }
    }
}

?>
