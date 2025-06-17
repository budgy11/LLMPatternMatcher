    <br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" placeholder="Enter password">
    <br><br>

    <button type="submit">Register</button>
  </form>

</body>
</html>


<?php

// Database configuration (Replace with your actual credentials)
$db_host = "localhost";
$db_user = "your_username";
$db_password = "your_password";
$db_name = "your_database_name";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Validate and sanitize the input data
  $username = filter_input(INPUT_POST, "username", FILTER_SANITIZE_STRING);
  $email = filter_input(INPUT_POST, "email", FILTER_VALIDATE_EMAIL);
  $password = filter_input(INPUT_POST, "password", FILTER_SANITIZE_STRING);

  // Password validation (basic example - consider stronger validation)
  if (strlen($password) < 8) {
    $password_error = "Password must be at least 8 characters long.";
  }

  // Check if required fields are filled
  if (empty($username) || empty($email) || empty($password)) {
    $username_error = "Username cannot be empty.";
    $email_error = "Email cannot be empty.";
    $password_error = "Password cannot be empty.";
  }

  // Check if username already exists
  $sql = "SELECT * FROM users WHERE username = '$username'";
  $result = mysqli_query($GLOBALS['db_host'], $sql);

  if (mysqli_num_rows($result) > 0) {
    $username_error = "Username already exists.";
  }

  // Hash the password before storing it
  $hashed_password = password_hash($password, PASSWORD_DEFAULT);


  // If all validation passes, insert the user into the database
  if (empty($username_error) && empty($email_error) && empty($password_error)) {
    $sql = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$hashed_password')";

    if (mysqli_query($GLOBALS['db_host'], $sql)) {
      echo "Registration successful! Please check your email for verification instructions.";
    } else {
      echo "Error: " . mysqli_error($GLOBALS['db_host']);
    }
  }
}
?>
