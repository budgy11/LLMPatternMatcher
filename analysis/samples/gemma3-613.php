
<!DOCTYPE html>
<html>
<head>
  <title>Login</title>
</head>
<body>

  <h2>Login</h2>

  <form action="login.php" method="post">
    Username: <input type="text" name="username">
    Password: <input type="password" name="password">
    <button type="submit">Login</button>
  </form>

</body>
</html>


<?php

// Database credentials - Replace with your actual credentials
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
    $sql = "SELECT * FROM users WHERE username = ? AND password = ?";

    // Prepare the statement
    $stmt = $conn->prepare($sql);

    // Bind parameters
    $stmt->bind_param("ss", $username, $password); // "ss" indicates two string parameters

    // Execute the query
    if ($stmt->execute() === TRUE) {
        // Get the result
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            // User found - get the user data
            $row = $result->fetch_assoc();
            // Set session variables
            $_SESSION['user_id'] = $row['id']; // Assuming you have an 'id' column in your users table
            $_SESSION['username'] = $row['username'];

            // Optionally redirect the user to a protected page
            // header("Location: protected_page.php");
            // exit();

            return true; // Login successful
        } else {
            return false; // User not found or credentials incorrect
        }

    } else {
        return false; // Query execution error
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
}

// Example usage:
// Check if the login form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Validate input (important for security)
    if (empty($username) || empty($password)) {
        $error = "Username and password must be filled.";
    } else {
        $loginResult = loginUser($username, $password);

        if ($loginResult) {
            if (!isset($_SESSION['user_id'])) {
                echo "Login successful!  You are now logged in.";
                // Redirect to a secure page
            } else {
              echo "Login successful! You are now logged in.";
            }
        } else {
            $error = "Invalid username or password.";
        }
    }
}

?>
