
</body>
</html>


<?php

// Database connection details (replace with your actual values)
$host = "localhost";
$username = "your_db_username";
$password = "your_db_password";
$database = "your_db_name";

// Form data from the login form
$username = $_POST["username"];
$password = $_POST["password"];

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // Validate input (important for security!)
  if (empty($username) || empty($password)) {
    $error = "Username and password are required.";
  } elseif (!filter_var($username, FILTER_VALIDATE_EMAIL)) {
    $error = "Invalid email address.";
  } else {
    // SQL query to authenticate the user
    $sql = "SELECT id, username, password FROM users WHERE username = '$username'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
      $row = mysqli_fetch_assoc($result);

      // Verify the password (using password_verify from PHP 8.1 and later, or manual comparison)
      if (password_verify($password, $row["password"])) {
        // Login successful
        // Set session variables to keep the user logged in
        session_start();
        $_SESSION["user_id"] = $row["id"];
        $_SESSION["username"] = $row["username"];
        echo "Login successful! <a href='dashboard.php'>Go to Dashboard</a>";
      } else {
        $error = "Incorrect password.";
      }
    } else {
      $error = "Username not found.";
    }
  }
} else {
  // If the form wasn't submitted via POST, redirect to the login form
  header("Location: login.php");
  exit();
}
?>
