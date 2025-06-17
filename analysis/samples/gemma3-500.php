    Username: <input type="text" name="username" required><br><br>
    Password: <input type="password" name="password" required><br><br>
    <input type="submit" value="Login">
  </form>

</body>
</html>


<?php

// Database credentials (replace with your actual values)
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "your_database_name";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get username and password from the form
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Connect to the database
  $conn = new mysqli($servername, $username, $password, $dbname);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // SQL query to check credentials
  $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
  $result = $conn->query($sql);

  if ($result->num_rows == 1) {
    // User found, set session variables
    session_start();
    $_SESSION["username"] = $username; // Store username in session
    // You can store other user data here if needed
    // For example: $_SESSION["user_id"] = $result->fetch_assoc()["user_id"];

    // Redirect to a protected page or home page
    header("Location: /protected_page.php"); // Replace with your protected page URL
    exit();  // Very important to stop further execution
  } else {
    // User not found
    echo "Invalid username or password.";
  }

  $conn->close();
}
?>
