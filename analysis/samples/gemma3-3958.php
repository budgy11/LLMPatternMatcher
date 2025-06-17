    <br><br>

    <label for="password">Password:</label><br>
    <input type="password" id="password" name="password" value="">
    <br><br>

    <input type="submit" value="Register">
  </form>

  <p>Already have an account? <a href="login.php">Login</a></p>

</body>
</html>


$conn = new mysqli("localhost", "your_username", "your_password", "my_app");

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}


<?php

// Database configuration (Replace with your actual database details)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_username";
$db_password = "your_password";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // Get form data
  $username = $_POST["username"];
  $email = $_POST["email"];
  $password = $_POST["password"];

  // Sanitize and validate input (IMPORTANT!)
  $username = filter_var($username, FILTER_SANITIZE_STRING);
  $email = filter_var($email, FILTER_SANITIZE_EMAIL);
  $password = filter_var($password, FILTER_SANITIZE_STRING);

  // Validate email format
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $error_message = "Invalid email address.";
  }

  // Password validation (Basic - improve this for production)
  if (empty($password)) {
    $error_message = "Password cannot be empty.";
  }

  // Check if username already exists
  $sql = "SELECT * FROM users WHERE username = '$username'";
  $result = mysqli_query($db_host, $sql);
  if (mysqli_num_rows($result) > 0) {
    $error_message = "Username already exists.";
  }

  // If no errors, insert the user into the database
  if (empty($error_message)) {
    // Hash the password (VERY IMPORTANT for security!)
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // SQL query for inserting the user
    $sql = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$hashed_password')";

    // Execute the query
    mysqli_query($db_host, $sql);

    // Redirect to a success page or login page
    header("Location: success.php"); // Replace 'success.php' with your desired page
    exit();
  }
}
?>
