    <label for="username">Username:</label>
    <input type="text" id="username" name="username" placeholder="Enter username"><br><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" placeholder="Enter email"><br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" placeholder="Enter password"><br><br>

    <button type="submit">Register</button>
  </form>

</body>
</html>


<?php

// Database connection details (replace with your actual credentials)
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

  // Sanitize and validate the data (VERY IMPORTANT!)
  $username = filter_var($username, FILTER_SANITIZE_STRING);
  $email = filter_var($email, FILTER_SANITIZE_EMAIL);
  $password = filter_var($password, FILTER_SANITIZE_STRING);

  // Validate required fields
  if (empty($username) || empty($email) || empty($password)) {
    $error_message = "All fields are required.";
  } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $error_message = "Invalid email format.";
  } elseif (strlen($password) < 8) {
    $error_message = "Password must be at least 8 characters long.";
  } else {
    // Hash the password (VERY IMPORTANT for security!)
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insert the new user into the database
    $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }

    $sql = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$hashed_password')";

    if ($conn->query($sql) === TRUE) {
      $success_message = "Registration successful! Please check your email to activate your account.";
    } else {
      $error_message = "Error inserting user. " . $conn->error;
    }

    $conn->close();
  }
}
?>
