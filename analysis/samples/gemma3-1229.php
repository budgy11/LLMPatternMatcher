    Username: <input type="text" name="username" required>
    <br><br>
    Password: <input type="password" name="password" required>
    <br><br>
    <button type="submit">Login</button>
  </form>

  <br><br>
  <a href="register.php">Don't have an account? Register here.</a>

</body>
</html>


// Registering a new user (example)
$hashed_password = password_hash("your_password", PASSWORD_DEFAULT);

// Storing the hashed password in the database (in the INSERT query)
// ...

// Logging in a user:
$password = $_POST["password"];
$hashed_password = password_hash($password, PASSWORD_DEFAULT);
// ... your SQL query ...


<?php

// Database Configuration (Replace with your actual credentials)
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

    // SQL query to select the user
    $sql = "SELECT * FROM users WHERE username = ? AND password = ?";  // Use prepared statements for security

    // Prepare the statement (important for security)
    $stmt = $conn->prepare($sql);

    // Bind parameters
    $stmt->bind_param("ss", $username, $password); // "ss" indicates two string parameters

    // Execute the query
    if ($stmt->execute()) {
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        if ($row) {
            // User found, set session variables
            session_start();  // Start session management
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['username'] = $row['username'];

            // Redirect to a protected page or display a success message
            header("Location: /protected_page.php"); // Replace with your protected page URL
            exit(); // Important to stop further execution
        } else {
            // User not found
            return false;
        }
    } else {
        // Query error
        return false;
    }

    // Close the statement
    $stmt->close();
    $conn->close();

    return true;
}


// Example Usage (Demonstration - DO NOT directly use this in your application)
// Assuming you have a form to collect username and password

// Check if the login form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Validate input (important to prevent SQL injection)
    if (empty($username) || empty($password)) {
        $error = "Username and password cannot be empty.";
    } else {
        $login_result = loginUser($username, $password);

        if ($login_result) {
            echo "Login successful!  Redirecting...";
        } else {
            echo "Login failed.  Invalid credentials.";
        }
    }
}

?>
