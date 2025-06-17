        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required>

        <br><br>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>

        <br><br>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>

        <br><br>

        <button type="submit">Register</button>
    </form>

    <p>Already have an account? <a href="login.php">Login</a></p>
</body>
</html>


<?php

// Database configuration (Replace with your actual database details)
$db_host = 'localhost';
$db_user = 'your_db_user';
$db_password = 'your_db_password';
$db_name = 'your_db_name';

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Validate the form data (Add more validation as needed)
  $username = trim($_POST["username"]);
  $email = trim($_POST["email"]);
  $password = trim($_POST["password"]);

  // Check if the username and email are empty
  if (empty($username) || empty($email) || empty($password)) {
    $error_message = "All fields are required.";
  } else {
    // Validate email format (basic check)
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $error_message = "Invalid email format.";
    }

    // Check if the username already exists
    $sql = "SELECT * FROM users WHERE username = '$username'";
    $result = mysqli_query($db_host, $sql);

    if (mysqli_num_rows($result) > 0) {
      $error_message = "Username already exists.";
    }

    // Hash the password (Important for security!)
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // If no errors, insert the user into the database
    if (empty($error_message)) {
      $sql = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$hashed_password')";
      $result = mysqli_query($db_host, $sql);

      if ($result) {
        $success_message = "Registration successful!  Please check your email to activate your account.";
      } else {
        $error_message = "Error inserting user into database. Please try again.";
      }
    }
  }
}
?>
