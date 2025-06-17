
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
    <button type="submit">Login</button>
  </form>

</body>
</html>


<?php

// Database credentials - Replace with your actual values
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "your_database";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get input values from the form
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Connect to the database
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Query to verify credentials
    $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // User found, set session variables
        $_SESSION["username"] = $username;  // Store username in session
        echo "Login successful!";
        // Redirect the user to a protected page or dashboard
        header("Location: dashboard.php"); // Replace 'dashboard.php' with your desired page
        exit();
    } else {
        echo "Invalid username or password.";
    }

    $conn->close();
}
?>
