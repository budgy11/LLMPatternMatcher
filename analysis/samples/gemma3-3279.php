    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required>

    <br><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required>

    <br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required>

    <br><br>

    <input type="submit" value="Register">
  </form>

</body>
</html>


<?php

// Include the database connection file (assuming it's named 'db_connect.php')
require_once 'db_connect.php';

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // Get form data
  $username = $_POST["username"];
  $email = $_POST["email"];
  $password = $_POST["password"];

  // Validation (Important!)
  if (empty($username) || empty($email) || empty($password)) {
    $error = "All fields are required.";
  } elseif (
    !preg_match("/^[a-zA-Z0-9._-]+$/", $username) ||  // Username validation
    !preg_match("/^[^\s@]+@[^\s@]+\.[^\s@]+$/", $email) || // Email validation
    $password < 6 // Minimum password length
  ) {
    $error = "Invalid username, email, or password.";
  } else {
    // Hash the password (Important for security)
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Prepare the SQL query
    $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";

    // Prepare and execute the query
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $username, $email, $hashed_password);  // s = string
    $stmt->execute();

    // Check if the query was successful
    if ($stmt->errno) {
      $error = "Query failed: " . $stmt->error;
    } else {
      $success = true;
      $message = "Registration successful.  Please check your email to activate your account.";
    }
  }
}

?>
