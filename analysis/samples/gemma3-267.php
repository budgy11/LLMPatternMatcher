
  <form action="" method="post">
    Username: <input type="text" name="username" required>
    Password: <input type="password" name="password" required>
    <input type="submit" value="Login">
  </form>

</body>
</html>


<?php

// Database connection details (replace with your actual values)
$servername = "localhost";
$username = "your_db_username";
$password = "your_db_password";
$dbname = "your_db_name";

// Form data from the login form
$username = $_POST["username"];
$password = $_POST["password"];

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// SQL query to check credentials
$sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // User found, set session variables
    session_start();
    $row = $result->fetch_assoc();
    $_SESSION["username"] = $row["username"];
    $_SESSION["user_id"] = $row["id"]; // Assuming you have an 'id' column in your users table
    // You can add more data you want to store in the session here.
    // For example: $_SESSION["role"] = $row["role"];

    // Redirect to a protected page
    header("Location: /protected_page.php"); // Replace with your protected page URL
    exit(); // Important: Exit after redirecting

} else {
    // User not found
    echo "Invalid username or password.";
}

$conn->close();

?>
