    <label for="firstname">First Name:</label>
    <input type="text" id="firstname" name="firstname" placeholder="Enter your first name" required>

    <br><br>

    <label for="lastname">Last Name:</label>
    <input type="text" id="lastname" name="lastname" placeholder="Enter your last name" required>

    <br><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" placeholder="Enter your email address" required>

    <br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" placeholder="Enter your password" required>

    <br><br>

    <input type="submit" value="Register">
  </form>

</body>
</html>


<?php

// Database credentials (Replace with your actual database details)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_db_user";
$db_password = "your_db_password";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Sanitize the input data (IMPORTANT for security)
  $username = trim($_POST["username"]);
  $email = trim($_POST["email"]);
  $password = trim($_POST["password"]);

  // Validate the input (Crucial for security and data integrity)
  if (empty($username) || empty($email) || empty($password)) {
    $error = "All fields are required.";
  } elseif (
    !preg_match("/^[a-zA-Z0-9._-]+$/", $username) || // Username validation
    !preg_match("/^[^\s@]+@[^\s@]+\.[^\s@]+$/", $email) // Email validation
  ) {
    $error = "Invalid username or email format.";
  } elseif (
    strlen($password) < 8  // Minimum password length
  ) {
    $error = "Password must be at least 8 characters long.";
  } else {
    // Hash the password (VERY IMPORTANT for security)
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insert the new user into the database
    $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }

    $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $username, $email, $hashed_password);
    $stmt->execute();

    if ($stmt->affected_rows == 0) {
      $error = "Failed to register.  Please try again.";
    }

    $stmt->close();
    $conn->close();
  }
}
?>
