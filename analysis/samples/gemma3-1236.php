    Username: <input type="text" name="username" required>
    Password: <input type="password" name="password" required>
    <input type="submit" value="Login">
  </form>

</body>
</html>


<?php

// Database credentials (replace with your actual credentials)
$username = "your_username";
$password = "your_password";
$database = "your_database";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get the username and password from the form
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Validate input (basic validation - improve this for a production environment)
  if (empty($username) || empty($password)) {
    $error_message = "Username and password cannot be empty.";
  } elseif (!filter_var($username, FILTER_VALIDATE_EMAIL)) {
    $error_message = "Invalid username (please enter a valid email address)";
  } else {
    // Database connection
    $conn = new mysqli($username, $password, $database);

    // Check connection
    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }

    // SQL query to authenticate the user
    $sql = "SELECT * FROM users WHERE email = '$username'"; // Use email for security
    $result = $conn->query($sql);

    if ($result->num_rows == 0) {
      $error_message = "Invalid username or password.";
    } else {
      // User found, check the password
      $user = $result->fetch_assoc();
      if (password_verify($password, $user["password"])) {
        // Authentication successful - set session variables
        session_start();
        $_SESSION["user_id"] = $user["id"];
        $_SESSION["username"] = $user["email"]; // Or username, depending on your database
        header("Location: welcome.php"); // Redirect to a welcome page
        exit();
      } else {
        $error_message = "Invalid username or password.";
      }
    }
  }
}
?>
