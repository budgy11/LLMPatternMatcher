

<?php

// Database credentials (Replace with your actual credentials!)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_username";
$db_pass = "your_password";


// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get username and password from the form
  $username = trim($_POST["username"]);
  $password = trim($_POST["password"]);

  // Validate input (Important for security)
  if (empty($username) || empty($password)) {
    $error = "Username and password cannot be empty.";
  } elseif (filter_var($username, FILTER_VALIDATE_EMAIL) === false) {
    $error = "Invalid email format.";
  } else {
    // Hash the password (using password_hash)
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Attempt to connect to the database
    $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

    // Check connection
    if ($conn->connect_error) {
      $error = "Connection failed: " . $conn->connect_error;
    } else {
      // Query to check for the username
      $sql = "SELECT * FROM users WHERE username = '$username'"; // Use single quotes for username
      $result = $conn->query($sql);

      if ($result->num_rows == 0) {
        $error = "Invalid username or password.";
      } else {
        // If the username exists, attempt to verify the password
        $row = $result->fetch_assoc();
        if (password_verify($password, $row["password"])) {
          // If the password matches, set a session variable
          $_SESSION["username"] = $username;
          $error = "Login successful!";
        } else {
          $error = "Invalid username or password.";
        }
      }
    }
  }
}
?>
