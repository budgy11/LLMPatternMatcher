

<?php

// Database configuration (replace with your actual database details)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_username";
$db_password = "your_password";

// Check if the login form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get the username and password from the form
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Validate the input (important for security)
  if (empty($username) || empty($password)) {
    $error_message = "Username and password cannot be empty.";
  } elseif (!filter_var($username, FILTER_VALIDATE_EMAIL) && !preg_match('/^[a-zA-Z0-9_]+$/', $username)) {
    $error_message = "Invalid username.  Please use a valid email address or alphanumeric username.";
  }  else {
    // Hash the password for security (using password_hash)
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Connect to the database
    $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

    // Check the connection
    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }

    // Query to check the credentials
    $sql = "SELECT * FROM users WHERE username = '$username'";
    $result = $conn->query($sql);

    // Check if the query returned any results
    if ($result->num_rows > 0) {
      // Get the user data
      $user = $result->fetch_assoc();

      // Verify the password
      if (password_verify($password, $user["password"])) {
        // Login successful
        // Session handling (to keep the user logged in)
        session_start();
        $_SESSION["username"] = $username;
        header("Location: welcome.php"); // Redirect to a welcome page or dashboard
        exit();
      } else {
        $error_message = "Incorrect password.";
      }
    } else {
      $error_message = "User not found.";
    }
  }
}
?>
