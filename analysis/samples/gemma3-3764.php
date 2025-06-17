    <label for="username">Username:</label><br>
    <input type="text" id="username" name="username" required><br><br>

    <label for="email">Email:</label><br>
    <input type="email" id="email" name="email" required><br><br>

    <label for="password">Password:</label><br>
    <input type="password" id="password" name="password" required><br><br>

    <input type="submit" value="Register">
  </form>

</body>
</html>


<?php

// Database connection details (Replace with your actual details)
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // Get form data
  $username = $_POST["username"];
  $email = $_POST["email"];
  $password = $_POST["password"];

  // Validate data (Important to prevent security vulnerabilities)
  if (empty($username) || empty($email) || empty($password)) {
    $error = "All fields are required.";
  } elseif (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
    $error = "Invalid email format.";
  } elseif (strlen($password) < 8) {
    $error = "Password must be at least 8 characters long.";
  }

  // Hash the password
  $hashed_password = password_hash($password, PASSWORD_DEFAULT);

  // SQL Query
  $sql = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$hashed_password')";

  // Execute the query
  if ($conn = new mysqli($host, $username, $password, $database)) {
    if ($conn->query($sql)) {
      $success = "Registration successful.  You have been redirected.";
      // Redirect to a success page or home page
      header("Location: success.php"); // Replace success.php with your desired location
      exit();
    } else {
      $error = "Error: " . $conn->error;
    }
  } else {
    $error = "Error: Unable to connect to the database.";
  }
}
?>
