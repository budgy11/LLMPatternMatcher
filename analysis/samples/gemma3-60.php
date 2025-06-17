    Username: <input type="text" name="username" required>
    Password: <input type="password" name="password" required>
    <button type="submit">Login</button>
</form>

</body>
</html>


<?php

// Database credentials (replace with your actual credentials)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_db_user";
$db_password = "your_db_password";

// Function to handle user login
function loginUser($username, $password) {
    // Connect to the database
    $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare the SQL query
    $sql = "SELECT * FROM users WHERE username = ? AND password = ?";

    // Bind parameters to prevent SQL injection
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $username, $password); // "ss" indicates two string parameters

    // Execute the query
    if ($stmt->execute() === TRUE) {
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();
            // Session handling (replace with your preferred method)
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['username'] = $row['username'];
            return true;
        } else {
            return false;
        }

    } else {
        return false;
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
}


// Example usage:

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get username and password from the form
    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);

    // Validate input (important for security)
    if (empty($username) || empty($password)) {
        echo "Username and password cannot be empty.";
    } else {
        // Call the loginUser function
        if (loginUser($username, $password)) {
            // Successful login
            echo "Login successful!  You are now logged in.";
            // Redirect to a protected page or display a welcome message.
            header("Location: /protected_page.php"); // Example redirect
            exit();

        } else {
            // Failed login
            echo "Invalid username or password.";
        }
    }
}
?>
