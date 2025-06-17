

<?php
session_start(); // Start the session

// Get the token from the URL
$token = $_GET['token'];

// Check if the token is valid (validate it against the database)
if (isset($token)) {
  // Retrieve the user ID from the token (this is just an example; you might
  // store the user ID in the token itself)

  // Validate the token
  $query = "SELECT id, user_id FROM users WHERE reset_token = '$token'";
  $result = mysqli_query($conn, $query);

  if ($result) {
    $row = mysqli_fetch_assoc($result);

    if ($row) {
      // Set a flag to indicate that the user is resetting their password
      $_SESSION['reset_token'] = $token;

      // Display the form for the user to enter a new password
      echo "<h1>Reset Password</h1>";
      echo "<form method='post' action='set_new_password.php'>";
      echo "<label for='new_password'>New Password:</label><br>";
      echo "<input type='password' id='new_password' name='new_password'><br><br>";
      echo "<input type='submit' value='Change Password'>";
      echo "</form>";
    } else {
      echo "Invalid token.";
    }
  } else {
    echo "Database error.";
  }
} else {
  echo "Invalid request.";
}
?>
