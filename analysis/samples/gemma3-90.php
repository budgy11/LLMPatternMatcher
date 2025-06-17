

<?php

// Database connection details (replace with your actual details)
$host = "localhost";
$username = "your_db_username";
$password = "your_db_password";
$database = "your_db_name";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get the username and password from the form
  $username = trim($_POST["username"]);
  $password = trim($_POST["password"]);

  // Validate the input (important for security!)
  if (empty($username) || empty($password)) {
    $error = "Username and password cannot be empty.";
  } elseif (!filter_var($username, FILTER_VALIDATE_EMAIL)) {
    $error = "Invalid email format.";
  } else {
    // Database query
    $query = "SELECT id, username, password FROM users WHERE username = '$username'";
    $result = mysqli_query($GLOBALS['conn'], $query);

    if (mysqli_num_rows($result) > 0) {
      $row = mysqli_fetch_assoc($result);

      // Verify the password (Important - compare with hash if you store hashed passwords)
      if (password_verify($password, $row["password"])) {
        // Login successful!  Set a session variable.
        session_start();
        $_SESSION["user_id"] = $row["id"];
        $_SESSION["username"] = $row["username"];
        
        // Redirect the user to a protected page (or display a success message)
        header("Location: /protected_page.php"); // Replace with your protected page URL
        exit();
      } else {
        $error = "Incorrect password.";
      }
    } else {
      $error = "Incorrect username.";
    }
  }
}
?>
