
<!DOCTYPE html>
<html>
<head>
  <title>User Login</title>
</head>
<body>

  <h2>User Login</h2>

  <form method="post" action="">
    Username: <input type="text" name="username">
    Password: <input type="password" name="password">
    <input type="submit" value="Login">
  </form>

</body>
</html>


<?php

// Database credentials (replace with your actual values)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_db_user";
$db_pass = "your_db_password";

// Function to handle user login
function loginUser($username, $password) {
    // Connect to the database
    $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare and execute the query
    $query = "SELECT * FROM users WHERE username = ? AND password = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $username, $password); // "ss" indicates two strings
    $stmt->execute();

    // Get the result
    $result = $stmt->get_result();

    // Check if a user was found
    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        // Session management
        session_start();
        $_SESSION['username'] = $user['username'];
        $_SESSION['user_id'] = $user['id']; // Assuming you have a user ID
        return true; // Login successful
    } else {
        return false; // Login failed
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
}

// Example usage (handling login form submission - demonstration only)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    if (loginUser($username, $password)) {
        header("Location: /welcome.php"); // Redirect to a welcome page
        exit();
    } else {
        echo "<p>Invalid username or password.</p>";
    }
}

?>
