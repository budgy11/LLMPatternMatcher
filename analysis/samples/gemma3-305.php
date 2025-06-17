    Username: <input type="text" name="username" <?php if (isset($username)) echo 'value="'. $username . '"'; ?> />
    <br />
    Password: <input type="password" name="password" />
    <br />
    <button type="submit">Login</button>
</form>

</body>
</html>


<?php

// Database credentials (replace with your actual credentials)
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database";

// Form data
$username = $_POST["username"];
$password = $_POST["password"];

// Connect to the database
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// SQL query to authenticate the user
$sql = "SELECT id, username, password FROM users WHERE username = '$username'";
$result = $conn->query($sql);

if ($result->num_rows == 1) {
  $row = $result->fetch_assoc();
  // Verify password
  if (password_verify($password, $row["password"])) {
    // Authentication successful
    // Set session variables
    session_start();
    $_SESSION["user_id"] = $row["id"];
    $_SESSION["username"] = $username;

    // Redirect to a protected page or display a welcome message
    header("Location: /protected_page.php"); // Replace with your protected page URL
    exit();
  } else {
    // Incorrect password
    echo "Incorrect password.";
  }
} else {
  // User not found
  echo "User not found.";
}

$conn->close();

?>
