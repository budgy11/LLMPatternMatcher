    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required><br><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required><br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required><br><br>

    <input type="submit" value="Register">
  </form>

</body>
</html>


<?php

// Database connection details (replace with your actual credentials)
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "your_database_name";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Validate the form data
  $username = trim($_POST["username"]);
  $email = trim($_POST["email"]);
  $password = trim($_POST["password"]);

  // Validate input (basic example - enhance this with more robust validation)
  if (empty($username) || empty($email) || empty($password)) {
    $error = "All fields are required.";
  } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $error = "Invalid email format.";
  } elseif (strlen($password) < 6) {
    $error = "Password must be at least 6 characters long.";
  }
  else {
    // Hash the password before storing it
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // SQL query to insert the new user
    $sql = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$hashed_password')";

    if (mysqli_query($GLOBALS['conn'], $sql)) {
      $success = true;
      $message = "Registration successful! Please check your email to verify.";
    } else {
      $error = "Error: " . mysqli_error($GLOBALS['conn']);
    }
  }
}

// Connect to the database
$GLOBALS['conn'] = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($GLOBALS['conn']->connect_error) {
  die("Connection failed: " . $GLOBALS['conn']->connect_error);
}


?>
