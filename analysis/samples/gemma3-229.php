
<!DOCTYPE html>
<html>
<head>
  <title>Login Form</title>
</head>
<body>

  <h1>Login</h1>

  <form action="login.php" method="POST">
    Username: <input type="text" name="username" required><br><br>
    Password: <input type="password" name="password" required><br><br>
    <input type="submit" value="Login">
  </form>

  <a href="register.php">Register</a>  <!-- Link to registration page -->

</body>
</html>


<?php

// ... (previous code) ...

// Register new user
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST["username"];
  $password = $_POST["password"];

  $servername = "localhost";
  $username = "your_username";
  $password = "your_password";
  $dbname = "your_database_name";

  // Create connection
  $conn = new mysqli($servername, $username, $password, $dbname);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // Hash the password
  $hashed_password = password_hash($password, PASSWORD_DEFAULT);

  // SQL query to insert new user
  $sql = "INSERT INTO users (username, password) VALUES ('$username', '$hashed_password')";
  if ($conn->query($sql) === TRUE) {
    echo "New record created successfully";
  } else {
    echo "Error: " . $sql . "<br>" . $conn->error;
  }

  $conn->close();
}
?>
