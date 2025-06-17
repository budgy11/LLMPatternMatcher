

<?php

// Database Credentials (Replace with your actual credentials)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_db_user";
$db_password = "your_db_password";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get the username and password from the form
  $username = trim($_POST["username"]);
  $password = trim($_POST["password"]);

  // Validate input (Crucial for security!)
  if (empty($username) || empty($password)) {
    $error_message = "Username and password cannot be empty.";
  } elseif (mysqli_query($conn, "SELECT * FROM users WHERE username = '$username'") === false) {
    $error_message = "Invalid username or password.";
  } else {
    // Password verification (Important!  Never store passwords in plain text)
    $hashed_password = "your_hashed_password"; // Replace with the actual hashed password from your database

    if (password_verify($password, $hashed_password)) {
      // Login successful!
      session_start();
      $_SESSION["username"] = $username;
      header("Location: welcome.php"); // Redirect to a welcome page
      exit;
    } else {
      $error_message = "Invalid username or password.";
    }
  }
}

// Database connection
$conn = mysqli_connect($db_host, $db_user, $db_password, $db_name);

// Check connection
if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}

?>
