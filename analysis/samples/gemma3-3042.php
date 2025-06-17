    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required>

    <button type="submit">Register</button>
</form>

</body>
</html>


<?php

// Database credentials (Replace with your actual values)
$db_host = 'localhost';
$db_name = 'your_database_name';
$db_user = 'your_database_user';
$db_pass = 'your_database_password';

// Check if the registration form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // Validate input - Crucial for security!
  $username = trim($_POST["username"]);
  $password = trim($_POST["password"]);
  $email = trim($_POST["email"]);

  // Validate required fields
  if (empty($username) || empty($password) || empty($email)) {
    $error_message = "All fields are required.";
  } elseif (strlen($username) < 3) {
    $error_message = "Username must be at least 3 characters long.";
  } elseif (strlen($password) < 6) {
    $error_message = "Password must be at least 6 characters long.";
  } elseif (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
    $error_message = "Invalid email address.";
  } else {

    // Hash the password - Very important for security
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Connect to the database
    $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

    // Check connection
    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }

    // Prepare the SQL query
    $sql = "INSERT INTO users (username, password, email) VALUES (?, ?, ?)";

    // Use prepared statements to prevent SQL injection
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $username, $hashed_password, $email);

    // Execute the query
    if ($stmt->execute()) {
      // Registration successful
      echo "Registration successful! Please check your email for verification.";
    } else {
      // Registration failed
      echo "Registration failed: " . $stmt->error;
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
  }
}
?>
