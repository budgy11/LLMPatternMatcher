

<?php

// Database Credentials (Replace with your actual credentials)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_database_user";
$db_pass = "your_database_password";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get the username and password from the form
  $username = trim($_POST["username"]);
  $password = trim($_POST["password"]);

  // Validate the inputs (Important for security!)
  if (empty($username) || empty($password)) {
    $error_message = "Username and password cannot be empty.";
  } else {
    // Hash the password for security
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Attempt to authenticate the user
    // Assuming you have a database table 'users' with columns 'username' and 'password'
    $sql = "SELECT id, username, password FROM users WHERE username = '$username'";
    $result = mysqli_query($GLOBALS['conn'], $sql);

    if (mysqli_num_rows($result) > 0) {
      $row = mysqli_fetch_assoc($result);
      if (password_verify($password, $row["password"])) {
        // If the password matches, set a session variable to store the user's ID
        session_start();
        $_SESSION["user_id"] = $row["id"];
        $_SESSION["username"] = $username; //optional, but useful
        // Redirect to a secure page
        header("Location: secure_page.php"); // Replace 'secure_page.php' with your secure page
        exit();
      } else {
        $error_message = "Incorrect password.";
      }
    } else {
      $error_message = "Incorrect username.";
    }
  }
}

// Start the session (if not already started)
if (session_status() == 'closed') {
  session_start();
}


// Display the login form
?>
