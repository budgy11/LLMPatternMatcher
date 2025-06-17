    Username: <input type="text" name="username" placeholder="Enter your username" value="<?php if (isset($username)) echo htmlspecialchars($username); ?>">
    <br><br>
    Password: <input type="password" name="password" placeholder="Enter your password">
    <br><br>
    <button type="submit">Login</button>
  </form>

  <br><br>
  <a href="registration.php">New User? Register here.</a>

</body>
</html>


<?php

// Database credentials (replace with your actual credentials)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_username";
$db_password = "your_password";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get the username and password from the form
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Validate the input (basic example - improve this significantly for production)
  if (empty($username) || empty($password)) {
    $error_message = "Username and password cannot be empty.";
  } elseif (trim($username) == "" || trim($password) == "") {
    $error_message = "Username and password cannot be empty.";
  }
  else {
    // Database connection
    $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

    // Check connection
    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }

    // SQL query to authenticate the user
    $sql = "SELECT id, username, password FROM users WHERE username = '$username'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
      $row = $result->fetch_assoc();
      // Verify the password (hash comparison - VERY IMPORTANT!)
      if (password_verify($password, $row["password"])) {
        // Login successful
        session_start(); // Start the session
        $_SESSION["user_id"] = $row["id"];
        $_SESSION["username"] = $username;
        header("Location: welcome.php"); // Redirect to a welcome page
        exit();
      } else {
        $error_message = "Incorrect password.";
      }
    } else {
      $error_message = "Username not found.";
    }
  }
}
?>
