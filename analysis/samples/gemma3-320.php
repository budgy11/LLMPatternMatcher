    Username: <input type="text" name="username" required>
    Password: <input type="password" name="password" required>
    <button type="submit">Login</button>
  </form>

</body>
</html>


<?php

// Database Credentials (Replace with your actual credentials)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_db_user";
$db_password = "your_db_password";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // Get username and password from the form
  $username = trim($_POST["username"]);
  $password = trim($_POST["password"]);

  // Validate input (Crucial for security!)
  if (empty($username) || empty($password)) {
    $error_message = "Username and password cannot be empty.";
  } elseif (mysqli_query($conn, "SELECT * FROM users WHERE username = '$username'") && mysqli_fetch_row(mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE username = '$username'"))) !== false) {
    // User exists, attempt to verify password
    $hashed_password = password_hash($_POST["password"], PASSWORD_DEFAULT);

    $result = mysqli_query($conn, "SELECT * FROM users WHERE username = '$username' AND password = '$hashed_password'");

    if ($result) {
      $user_data = mysqli_fetch_assoc($result);

      // If user found, set session variables
      session_start();
      $_SESSION["user_id"] = $user_data["id"];
      $_SESSION["username"] = $user_data["username"];

      // Redirect to a success page or the homepage
      header("Location: success.php"); // Replace 'success.php' with your desired redirect
      exit();
    } else {
      $error_message = "Incorrect password.";
    }
  } else {
    $error_message = "Invalid username or password.";
  }
}
else{
   // If form is not submitted, display the login form
   $error_message = "";
}

?>
