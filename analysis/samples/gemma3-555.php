
<!DOCTYPE html>
<html>
<head>
  <title>PHP Login Example</title>
</head>
<body>

  <h2>Login</h2>

  <form method="post" action="">
    Username: <input type="text" name="username" required><br><br>
    Password: <input type="password" name="password" required><br><br>
    <input type="submit" value="Login">
  </form>

</body>
</html>


function registerUser($username, $password) {
  // ... database connection ...

  $hashed_password = password_hash($password, PASSWORD_DEFAULT);

  // ... Prepare and execute the query, using $hashed_password instead of $password ...

  // ... Close connection ...
}


<?php

// Database credentials (replace with your actual values)
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "your_database";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get input values
  $username = trim($_POST["username"]);
  $password = trim($_POST["password"]);

  // Validate input (important for security)
  if (empty($username) || empty($password)) {
    $error[] = "Username and Password must be filled.";
  }

  // Connect to the database
  $conn = new mysqli($servername, $username, $password, $dbname);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // Prepare the SQL query
  $sql = "SELECT id, username, password FROM users WHERE username = '$username'";
  $result = $conn->query($sql);

  // Check if the query returned any rows
  if ($result->num_rows > 0) {
    // Get data from the result
    $row = $result->fetch_assoc();

    // Verify the password
    if (password_verify($password, $row["password"])) {
      // Password matches!  User is authenticated
      // Set session variables for the user
      session_start();
      $_SESSION["user_id"] = $row["id"];
      $_SESSION["username"] = $row["username"];
      echo "Login successful!  You are now logged in.";
      header("Location: user_dashboard.php"); // Redirect to a dashboard page
      exit(); // Important: Stop further script execution after successful login
    } else {
      $error[] = "Incorrect password.";
    }
  } else {
    $error[] = "User not found.";
  }
}
?>
