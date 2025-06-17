    Username: <input type="text" name="username" />
    Password: <input type="password" name="password" />
    <button type="submit">Login</button>
  </form>

  <br>
  <a href="register.php">Don't have an account? Register here.</a>

</body>
</html>


<?php

// Database Configuration (Replace with your actual credentials)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_username";
$db_password = "your_password";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get form data
  $username = trim($_POST["username"]);
  $password = trim($_POST["password"]);

  // Validate input (Important for security)
  if (empty($username) || empty($password)) {
    $error_message = "Username and Password cannot be empty.";
  } elseif (filter_var($username, FILTER_SANITIZE_STRING) === false) {
    $error_message = "Invalid Username.  Must contain letters and numbers.";
  }
  // Hash the password for security
  $hashed_password = password_hash($password, PASSWORD_DEFAULT);

  // Connect to the database
  $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // SQL query to check credentials
  $sql = "SELECT id, username, password FROM users WHERE username = '$username'";
  $result = $conn->query($sql);

  // Check if the query returned any results
  if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    // Verify the password
    if (password_verify($password, $row["password"])) {
      // Login successful
      // Session handling for persistent login
      session_start();
      $_SESSION["user_id"] = $row["id"];
      $_SESSION["username"] = $row["username"];

      // Redirect to a secure page or display a success message
      header("Location: secure_page.php"); // Replace with your secure page
      exit();
    } else {
      $error_message = "Incorrect password.";
    }
  } else {
    $error_message = "User not found.";
  }
} else {
  // Display the login form
  $error_message = ""; // Clear any previous errors
}
?>
