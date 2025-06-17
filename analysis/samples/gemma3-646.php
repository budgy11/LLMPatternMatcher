    Username: <input type="text" name="username">
    Password: <input type="password" name="password">
    <button type="submit">Login</button>
</form>

</body>
</html>


<?php

// Database Configuration (Replace with your actual database details)
$host = "localhost";
$username = "your_db_username";
$password = "your_db_password";
$database = "your_db_name";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get the username and password from the form
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Validate input (Important for security -  see below)
  if (empty($username) || empty($password)) {
    $error = "Username and Password cannot be empty.";
  } else {
    // Connect to the database
    $conn = new mysqli($host, $username, $password, $database);

    // Check the connection
    if ($conn->connect_error) {
      $error = "Connection failed: " . $conn->connect_error;
    } else {
      // SQL query to authenticate the user
      $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
      $result = $conn->query($sql);

      if ($result->num_rows == 1) {
        // User found - Set session variables
        session_start();
        $_SESSION["username"] = $username;
        $_SESSION["user_id"] = $result->fetch_assoc()["id"]; // Assuming you have an 'id' column
        $error = "Login successful!";
        // Redirect to a secure page (e.g., dashboard)
        header("Location: dashboard.php"); // Or your desired secure page
        exit();
      } else {
        $error = "Invalid username or password.";
      }
    }
  }
}
?>
