

<?php

// Database configuration (Replace with your actual credentials)
$db_host = "localhost";
$db_user = "your_db_user";
$db_password = "your_db_password";
$db_name = "your_db_name";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // Get username and password from the form
  $username = trim($_POST["username"]);
  $password = trim($_POST["password"]);

  // Validate input (Important for security!)
  if (empty($username) || empty($password)) {
    $error = "Username and Password cannot be empty.";
  } elseif (mysqli_num_rows(mysqli_query($conn, "SELECT * FROM users WHERE username = '$username'") == 0) ) {
    $error = "Invalid username or password.";
  } else {
    // Authenticate the user
    $password_hash = password_hash($password, PASSWORD_DEFAULT); // Hash the password
    $query = "SELECT * FROM users WHERE username = '$username'";
    $result = mysqli_query($conn, $query);
    $user = mysqli_fetch_assoc($result);

    // Check if the password matches
    if (password_verify($password, $user["password_hash"])) {
      // Login successful!  Set a session variable.
      $_SESSION["user_id"] = $user["id"]; // Store the user ID in the session
      $_SESSION["username"] = $user["username"];
      header("Location: welcome.php"); // Redirect to a welcome page
      exit();
    } else {
      $error = "Incorrect password.";
    }
  }
}
?>
