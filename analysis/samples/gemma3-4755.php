    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required><br><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required><br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required><br><br>

    <button type="submit">Register</button>
  </form>

</body>
</html>


<?php

// Database credentials - REPLACE with your actual values!
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_database_user";
$db_pass = "your_database_password";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Validate the form data (very basic example - improve this for production)
  $username = trim($_POST["username"]);
  $email = trim($_POST["email"]);
  $password = trim($_POST["password"]);

  // Validate username (e.g., minimum length, allowed characters)
  if (empty($username) || strlen($username) < 5) {
    $username_error = "Username must be at least 5 characters long.";
  }

  // Validate email (basic format check)
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $email_error = "Invalid email format.";
  }

  // Validate password (e.g., minimum length, complexity)
  if (empty($password) || strlen($password) < 8) {
    $password_error = "Password must be at least 8 characters long.";
  }


  // If validation passes, proceed with registration
  if (empty($username_error) && empty($email_error) && empty($password_error)) {

    // Hash the password (VERY IMPORTANT for security!)
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // SQL query to insert the new user
    $sql = "INSERT INTO users (username, email, password)
            VALUES ('$username', '$email', '$hashed_password')";

    // Execute the query
    $result = mysqli_query($GLOBALS['db_host'], $sql);  // Use mysqli_query

    if ($result) {
      // Registration successful
      $success_message = "Registration successful! Please check your email to activate your account.";
    } else {
      // Registration failed
      $error_message = "Error registering user. Please try again.";
    }
  }
}
?>
