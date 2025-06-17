    <br><br>

    <input type="submit" value="Register">
  </form>

</body>
</html>


<?php

// Database connection details - Replace with your actual details
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

  // Validate the data (Crucial for security!)
  if (empty($username) || empty($email) || empty($password)) {
    $errors = "All fields are required.";
  } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors = "Invalid email format.";
  } elseif (strlen($password) < 8) {
    $errors = "Password must be at least 8 characters long.";
  }
  // Hash the password (VERY IMPORTANT!)
  $hashed_password = password_hash($password, PASSWORD_DEFAULT);

  // Prepare the SQL query
  $sql = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$hashed_password')";

  // Execute the query
  if (mysqli_query($GLOBALS["conn"], $sql)) {
    $success = "Registration successful. Please check your email to verify your account.";
  } else {
    $errors = "Error registering user. " . mysqli_error($GLOBALS["conn"]);
  }
} else {
  // If the form hasn't been submitted, initialize the form with empty values
  $errors = "";
}

// Database connection
$conn = mysqli_connect($db_host, $db_user, $db_password, $db_name);
if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}

?>
