        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required><br><br>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br><br>

        <button type="submit">Login</button>
    </form>

</body>
</html>


<?php

// Database credentials (replace with your actual values)
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get username and password from the form
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Validate input (important for security!)
  if (empty($username) || empty($password)) {
    $error = "Username and password cannot be empty.";
  } else {
    // Database connection
    $conn = new mysqli($host, $username, $password, $database);

    // Check connection
    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }

    // Query to check username and password
    $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
    $result = $conn->query($sql);

    if ($result->num_rows == 0) {
      $error = "Invalid username or password.";
    }

    if ($result->num_rows > 0) {
      // User found, set session variables and redirect
      session_start();
      $_SESSION["username"] = $username; // Store the username
      // Optionally, store other user data (e.g., user ID)
      // $_SESSION["user_id"] = $user_id;

      header("Location: welcome.php"); // Redirect to a welcome page
      exit(); // Important: Stop further script execution
    }
  }
}
?>
