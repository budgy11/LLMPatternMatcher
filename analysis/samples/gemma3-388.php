
    <form method="post" action="">
        Username: <input type="text" name="username" required>
        Password: <input type="password" name="password" required>
        <input type="submit" value="Login">
    </form>

    <p>Don't have an account? <a href="register.php">Register</a></p>  <!-- Link to registration page -->

</body>
</html>


<?php

// Database connection details
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "your_database";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // Get username and password from the form
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Validate input (important for security!)
  if (empty($username) || empty($password)) {
    $error[] = "Username and password must be filled.";
  }

  // Connect to the database
  $conn = new mysqli($servername, $username, $password, $dbname);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // SQL query to authenticate the user
  $sql = "SELECT id, username, password FROM users WHERE username = '$username'";
  $result = $conn->query($sql);

  if ($result->num_rows == 0) {
    $error[] = "Invalid username or password.";
  } else {
    // If the query returns a row, it means the user exists
    $row = $result->fetch_assoc();
    // Verify the password
    if (password_verify($password, $row["password"])) {
      // Password is correct!  Set session variables
      session_start();
      $_SESSION["username"] = $username; // Store the username in the session
      $_SESSION["id"] = $row["id"];  // Optionally, store the user ID as well
      echo "Login successful!";
      header("Location: welcome.php"); // Redirect to a welcome page
      exit();
    } else {
      $error[] = "Incorrect password.";
    }
  }

  // If there are errors, display them
  if (count($error) > 0) {
    echo "<ul>";
    foreach ($error as $err) {
      echo "<li>" . $err . "</li>";
    }
    echo "</ul>";
  }
}
?>
