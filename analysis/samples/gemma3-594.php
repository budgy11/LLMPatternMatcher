
<!DOCTYPE html>
<html>
<head>
  <title>Login</title>
</head>
<body>

  <h1>Login</h1>

  <form method="post" action="">
    Username: <input type="text" name="username" required><br><br>
    Password: <input type="password" name="password" required><br><br>
    <input type="submit" value="Login">
  </form>

</body>
</html>


<?php

// Database connection details
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database";

// Form data for login
$login_username = $_POST["username"] ?? ""; // Use null coalescing operator
$login_password = $_POST["password"] ?? "";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // Validate the input (Crucially important to prevent SQL injection)
  if (empty($login_username) || empty($login_password)) {
    $error_message = "Username and password cannot be empty.";
  } else {
    // Sanitize the inputs - very important to prevent SQL injection
    $login_username = filter_var($login_username, FILTER_SANITIZE_STRING);
    $login_password = filter_var($login_password, FILTER_SANITIZE_STRING);

    // SQL query to select the user
    $sql = "SELECT * FROM users WHERE username = '$login_username' AND password = '$login_password'";
    $result = mysqli_query($host, $sql);

    if (mysqli_num_rows($result) == 1) {
      // User found!

      //  Retrieve user data (customize based on your user table structure)
      $user_data = mysqli_fetch_assoc($result);

      //  Set a session variable to store the user's information
      session_start();
      $_SESSION["user_id"] = $user_data["id"]; // Assuming your users table has an 'id' column
      $_SESSION["username"] = $user_data["username"];
      //  You might want to store other user details like role, etc.

      // Redirect to a protected page or display a success message
      header("Location: secure_page.php"); // Replace 'secure_page.php' with your protected page
      exit(); // Important to stop further execution

    } else {
      // User not found
      $error_message = "Invalid username or password.";
    }
  }
}
?>
