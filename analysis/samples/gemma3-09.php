

<?php

// Database Credentials - Replace with your actual details
$db_host = "localhost";
$db_username = "your_username";
$db_password = "your_password";
$db_name = "your_database";

// Login form handling
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Validate input (important for security!)
  if (empty($username) || empty($password)) {
    $error = "Username and password cannot be empty.";
  } else {
    // Hash the password for secure storage (important!)
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Query the database
    $sql = "SELECT id, username, password FROM users WHERE username = '$username'";
    $result = mysqli_query($db_host, $sql);

    if (mysqli_num_rows($result) > 0) {
      $user = mysqli_fetch_assoc($result);

      // Verify the password
      if (password_verify($password, $user["password"])) {
        // Login successful!  Set session variables.
        session_start();
        $_SESSION["user_id"] = $user["id"];
        $_SESSION["username"] = $user["username"];

        // Redirect to a protected page
        header("Location: protected_page.php"); // Replace with your protected page
        exit();
      } else {
        $error = "Incorrect password.";
      }
    } else {
      $error = "Incorrect username or password.";
    }
  }
}
?>
