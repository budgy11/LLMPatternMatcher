    <label for="username">Username:</label><br>
    <input type="text" id="username" name="username" required><br><br>

    <label for="email">Email:</label><br>
    <input type="email" id="email" name="email" required><br><br>

    <label for="password">Password:</label><br>
    <input type="password" id="password" name="password" required><br><br>

    <button type="submit">Register</button>
</form>

</body>
</html>


<?php

// Database connection details (Replace with your actual credentials)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_db_user";
$db_pass = "your_db_password";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // Get form data
  $username = $_POST["username"];
  $email = $_POST["email"];
  $password = $_POST["password"];
  $confirm_password = $_POST["confirm_password"];

  // Validate the data
  if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
    $error = "All fields are required.";
  } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $error = "Invalid email format.";
  } elseif ($password != $confirm_password) {
    $error = "Passwords do not match.";
  }

  // Hash the password using password_hash()
  $hashed_password = password_hash($password, PASSWORD_DEFAULT);

  // Check if the validation passed
  if (!isset($error)) {
    // Insert the new user into the database
    $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

    if ($conn->connect_error) {
      $error = "Connection failed: " . $conn->connect_error;
    } else {
      // Prepare the SQL query
      $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";

      // Use prepared statements to prevent SQL injection
      $stmt = $conn->prepare($sql);
      $stmt->bind_param("sss", $username, $email, $hashed_password);

      // Execute the query
      if ($stmt->execute()) {
        // Registration successful
        $success = true;
      } else {
        // Registration failed
        $error = "Registration failed: " . $stmt->error;
      }
    }
  }
}
?>
