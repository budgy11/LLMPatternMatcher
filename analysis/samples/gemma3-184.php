    Username: <input type="text" name="username" required>
    Password: <input type="password" name="password" required>
    <input type="submit" value="Login">
  </form>

</body>
</html>


<?php

// Database credentials (replace with your actual credentials)
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "your_database_name";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get the username and password from the form
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Connect to the database
  $conn = new mysqli($servername, $username, $password, $dbname);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // SQL query to check the credentials
  $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
  $result = $conn->query($sql);

  // Check if the query returned any results
  if ($result->num_rows > 0) {
    // User found, set session variables
    $_SESSION["username"] = $username;
    echo "Login successful!  Redirecting...";
    // Redirect to a secure page (e.g., dashboard)
    header("Location: dashboard.php");
    exit(); // Important to stop further script execution
  } else {
    echo "Invalid username or password.";
  }

  $conn->close();
}
?>
