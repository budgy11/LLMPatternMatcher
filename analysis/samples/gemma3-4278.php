    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required><br><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required><br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required><br><br>

    <label for="confirm_password">Confirm Password:</label>
    <input type="password" id="confirm_password" name="confirm_password" required><br><br>

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
  // Get form data
  $username = $_POST["username"];
  $email = $_POST["email"];
  $password = $_POST["password"];

  // Sanitize and validate the data (IMPORTANT!)
  $username = filter_var($username, FILTER_SANITIZE_STRING);
  $email = filter_var($email, FILTER_SANITIZE_EMAIL);
  $password = filter_var($password, FILTER_SANITIZE_STRING);

  // Check if username and email are empty
  if (empty($username) || empty($email) || empty($password)) {
    $error_message = "All fields are required.";
  } else {
    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $error_message = "Invalid email format.";
    }
    // Check if username already exists (example - adjust to your database schema)
    $sql = "SELECT * FROM users WHERE username = '$username'";
    $result = mysqli_query($db_host, $sql);
    if (mysqli_num_rows($result) > 0) {
      $error_message = "Username already exists.";
    }
    // Hash the password (Important for security!)
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insert the user into the database
    $sql = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$hashed_password')";
    if (mysqli_query($db_host, $sql)) {
      $success_message = "Registration successful!  Please check your email to verify your account.";
    } else {
      $error_message = "Error inserting user into database.  " . mysqli_error($db_host);
    }
  }
}
?>
