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

// Database connection details (Replace with your actual details)
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database";

// Form handling (POST request)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Sanitize inputs
  $username = filter_input(INPUT_POST, "username", FILTER_SANITIZE_STRING);
  $email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL);
  $password = filter_input(INPUT_POST, "password", FILTER_SANITIZE_STRING);

  // Validate inputs
  $username = trim($username); // Remove whitespace
  $email = filter_input(INPUT_POST, "email", FILTER_VALIDATE_EMAIL); // More robust email validation
  $password = filter_input(INPUT_POST, "password", FILTER_SANITIZE_STRING);

  // Check if required fields are filled
  if (empty($username) || empty($email) || empty($password)) {
    $errors = array("error" => "All fields are required.");
  } elseif (empty($errors)) {
    // Check if username already exists
    $sql = "SELECT username FROM users WHERE username = '$username'";
    $result = mysqli_query($GLOBALS['conn'], $sql);
    $row = mysqli_fetch_assoc($result);

    if ($row) {
      $errors = array("error" => "Username already exists.");
    } elseif (empty($errors)) {
      // Hash the password before storing in the database
      $hashed_password = password_hash($password, PASSWORD_DEFAULT);

      // SQL query to insert the new user
      $sql = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$hashed_password')";

      if (mysqli_query($GLOBALS['conn'], $sql)) {
        // Registration successful
        echo "<p style='color: green;'>Registration successful.  Please check your email to confirm your account.</p>";
      } else {
        echo "<p style='color: red;'>Error: " . mysqli_error($GLOBALS['conn']) . "</p>";
      }
    }
  }
}
?>
