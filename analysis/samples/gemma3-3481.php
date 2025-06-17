    <label for="username">Username:</label>
    <input type="text" id="username" name="username" placeholder="Enter username" required>

    <br><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" placeholder="Enter email" required>

    <br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" placeholder="Enter password" required>

    <br><br>

    <button type="submit">Register</button>
  </form>

  <p>Already have an account? <a href="login.php">Log in</a></p>  <!-- Assuming you have a login page -->

</body>
</html>


<?php

// Database connection details (Replace with your actual values)
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "your_database_name";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // Validate the form data
  $username = filter_input(INPUT_POST, "username", FILTER_SANITIZE_STRING);
  $email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL);
  $password = filter_input(INPUT_POST, "password", FILTER_SANITIZE_STRING);

  // Validate email format
  if (empty($username) || empty($email) || empty($password)) {
    $error = "All fields are required.";
  } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $error = "Invalid email format.";
  } elseif (strlen($password) < 8) {
    $error = "Password must be at least 8 characters long.";
  } else {
    // Hash the password before storing it
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // SQL query to insert the new user
    $sql = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$hashed_password')";

    // Execute the query
    if (mysqli_query($GLOBALS["conn"], $sql)) {
      $success = "Registration successful. Please check your email for verification.";
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
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}


?>
