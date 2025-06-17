    <br><br>

    <button type="submit">Register</button>
  </form>

</body>
</html>


<?php

// Database Configuration (Replace with your actual credentials)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_database_user";
$db_password = "your_database_password";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Validate input (Crucial for security - sanitize and validate)
  $username = trim($_POST["username"]);
  $email = trim($_POST["email"]);
  $password = trim($_POST["password"]);

  // Basic input validation (Enhance this with more robust validation)
  if (empty($username) || empty($email) || empty($password)) {
    $error = "All fields are required.";
  } elseif (strlen($username) < 3) {
    $error = "Username must be at least 3 characters long.";
  } elseif (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
    $error = "Invalid email format.";
  } elseif (strlen($password) < 6) {
    $error = "Password must be at least 6 characters long.";
  } else {
    // Prepare the SQL query
    $sql = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$password')";

    // Execute the query
    if (mysqli_query($GLOBALS["conn"], $sql)) {
      $success = "Registration successful! Please log in.";
    } else {
      $error = "Error: " . mysqli_error($GLOBALS["conn"]);
    }
  }
} else {
  // If the form hasn't been submitted, clear any previous errors
  $error = "";
  $success = "";
}

// Connect to the database
$GLOBALS["conn"] = mysqli_connect($db_host, $db_user, $db_password, $db_name);

// Check the connection
if ($GLOBALS["conn"]->connect_error) {
  die("Connection failed: " . $GLOBALS["conn"]->connect_error);
}

?>
