    Username: <input type="text" name="username" required><br><br>
    Password: <input type="password" name="password" required><br><br>
    <input type="submit" value="Login">
  </form>

</body>
</html>


<?php

// Database connection details
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "your_database_name";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get the username and password from the form
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Validate the input
  if (empty($username) || empty($password)) {
    $error = "Username and password must be filled.";
  } elseif (!filter_var($username, FILTER_VALIDATE_EMAIL)) {
    $error = "Invalid username (must be a valid email address).";
  } else {
    // Attempt to connect to the database
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }

    // Query to verify the username and password
    $sql = "SELECT * FROM users WHERE email = '$username'";  // Assuming username is email address
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
      $row = $result->fetch_assoc();

      // Verify the password
      if (password_verify($password, $row["password"])) {
        // Login successful
        $_SESSION["username"] = $username;
        $success = "Login successful!";
        header("Location: welcome.php"); // Redirect to a welcome page
        exit;
      } else {
        $error = "Incorrect password.";
      }
    } else {
      $error = "Incorrect username or password.";
    }
  }
}
?>
