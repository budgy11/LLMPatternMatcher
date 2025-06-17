
<!DOCTYPE html>
<html>
<head>
  <title>Login</title>
</head>
<body>

  <h2>Login</h2>

  <form method="post" action="">
    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required><br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required><br><br>

    <button type="submit">Login</button>
  </form>

</body>
</html>


<?php

// Database configuration
$host = "localhost";
$username = "your_username"; // Replace with your database username
$password = "your_password"; // Replace with your database password
$database = "your_database"; // Replace with your database name

// Function to handle user login
function loginUser($username, $password) {
    // Connect to the database
    $conn = new mysqli($host, $username, $password, $database);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // SQL query to check login credentials
    $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // User found, set session variables
        $user = $result->fetch_assoc();
        $_SESSION['username'] = $user['username'];
        $_SESSION['userid'] = $user['id']; // Assuming you have an 'id' column in your users table
        return true; // Login successful
    } else {
        return false; // Login failed
    }

    $conn->close();
}

// Example usage (handling form submission):
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    if (loginUser($username, $password)) {
        // Login successful, redirect to a protected page
        header("Location: /protected_page.php"); // Replace with the URL of your protected page
        exit();
    } else {
        // Login failed, display an error message
        echo "<p style='color: red;'>Invalid username or password.</p>";
    }
}

?>
