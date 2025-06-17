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

// Database configuration (replace with your actual database credentials)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_username";
$db_password = "your_password";

// Check if the registration form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // Get form data
  $username = $_POST["username"];
  $email = $_POST["email"];
  $password = $_POST["password"];
  $confirm_password = $_POST["confirm_password"];

  // Validate the form data (Important for security!)
  $errors = [];

  // Username validation
  if (empty($username)) {
    $errors["username"] = "Username cannot be empty.";
  }
  if (strlen($username) < 3) {
    $errors["username"] = "Username must be at least 3 characters.";
  }

  // Email validation
  if (empty($email)) {
    $errors["email"] = "Email cannot be empty.";
  }
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors["email"] = "Invalid email format.";
  }

  // Password validation
  if (empty($password)) {
    $errors["password"] = "Password cannot be empty.";
  }
  if (strlen($password) < 8) {
    $errors["password"] = "Password must be at least 8 characters.";
  }
  if ($password != $confirm_password) {
    $errors["password"] = "Passwords must match.";
  }

  // If there are no errors, proceed with registration
  if (empty($errors)) {

    // Hash the password (VERY IMPORTANT!) - Use password_hash()
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Connect to the database
    $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

    // Check the connection
    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }

    // Prepare and execute the SQL query
    $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $username, $email, $hashed_password);
    $stmt->execute();

    // Close the statement and connection
    $stmt->close();
    $conn->close();

    // Redirect to a success page
    header("Location: success.php?message=Registration successful!");
    exit();

  }
}
?>
