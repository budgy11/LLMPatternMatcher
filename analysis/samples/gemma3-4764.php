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

// Database connection details (replace with your actual details)
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database_name";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // Validate input (important for security!)
  $username = filter_input(INPUT_POST, "username", FILTER_SANITIZE_STRING);
  $email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL);
  $password = filter_input(INPUT_POST, "password", FILTER_SANITIZE_STRING);

  // Validate required fields
  if (empty($username) || empty($email) || empty($password)) {
    $error = "All fields are required.";
  } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $error = "Invalid email address.";
  } elseif (strlen($password) < 6) {
    $error = "Password must be at least 6 characters long.";
  }
   // Hash the password - VERY IMPORTANT FOR SECURITY
  $hashed_password = password_hash($password, PASSWORD_DEFAULT);


  // Connect to the database
  $conn = new mysqli($host, $username, $password, $database);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // Prepare the SQL query
  $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";

  // Prepare statement
  $stmt = $conn->prepare($sql);

  // Bind parameters
  $stmt->bind_param("sss", $username, $email, $hashed_password);

  // Execute the query
  if ($stmt->execute()) {
    $success = "Registration successful! You have been redirected.";
    // Optionally redirect to a success page
    // header("Location: success.php");
    // exit;
  } else {
    $error = "Registration failed: " . $stmt->error;
  }

  // Close the statement and connection
  $stmt->close();
  $conn->close();
}
?>
