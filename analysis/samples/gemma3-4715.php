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
$db_name = "your_database_name";
$db_user = "your_db_user";
$db_pass = "your_db_password";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Sanitize input data to prevent vulnerabilities
  $username = filter_input(INPUT_POST, "username", FILTER_SANITIZE_STRING);
  $email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL);
  $password = filter_input(INPUT_POST, "password", FILTER_SANITIZE_STRING);

  // Validate input data
  $username = trim($username);
  $email = trim($email);
  $password = trim($password);

  // Check if username and email are empty
  if (empty($username) || empty($email) || empty($password)) {
    $error_message = "All fields are required.";
  } else {
    // Check if username already exists
    $sql = "SELECT * FROM users WHERE username = '$username'";
    $result = mysqli_query($db_host, $sql);

    if (mysqli_num_rows($result) > 0) {
      $error_message = "Username already exists.";
    } else {
      // Check if email is valid
      if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_message = "Invalid email format.";
      }
      // Hash the password before storing it
      $hashed_password = password_hash($password, PASSWORD_DEFAULT);

      // Insert the new user into the database
      $sql = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$hashed_password')";

      if (mysqli_query($db_host, $sql)) {
        $success_message = "Registration successful!  Please check your email to confirm your account.";
      } else {
        $error_message = "Error inserting user: " . mysqli_error($db_host);
      }
    }
  }
}
?>
