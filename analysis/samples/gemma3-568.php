
  <form method="post" action="login.php">
    Username: <input type="text" name="username" required>
    Password: <input type="password" name="password" required>
    <input type="submit" value="Login">
  </form>

  <p>Don't have an account? <a href="register.php">Register</a></p>

</body>
</html>


<?php

// Database credentials - Replace with your actual details
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "your_database_name";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get username and password from the form
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Validate input (very important for security)
  if (empty($username) || empty($password)) {
    $error = "Username and password cannot be empty.";
  } elseif (!filter_var($username, FILTER_VALIDATE_EMAIL) && !filter_var($username, FILTER_VALIDATE_EMAIL)) { // Basic email validation (you can refine this)
    $error = "Invalid username or email address.";
  } else {
    // Connect to the database
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }

    // SQL query to check username and password
    $sql = "SELECT * FROM users WHERE username = '$username'";
    $result = $conn->query($sql);

    if ($result->num_rows == 0) {
      $error = "Invalid username or password.";
    }

    if ($result->num_rows > 0) {
      // User found, set session variables
      $row = $result->fetch_assoc();
      $_SESSION["username"] = $row["username"];
      $_SESSION["user_id"] = $row["id"]; // Assuming you have an 'id' column in the users table
      //  You might also want to check the password hash
      //  if (password_verify($password, $row["password_hash"])) {  // Using password_verify to compare with the hashed password
      //    $_SESSION["username"] = $row["username"];
      //  } else {
      //    $error = "Invalid password.";
      //  }

      // Redirect to a secure page
      header("Location: secure_page.php"); // Replace 'secure_page.php' with your desired secure page
      exit();
    }
  }
}
?>
