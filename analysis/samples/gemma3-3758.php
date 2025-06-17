    <br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" value="">
    <br><br>

    <button type="submit">Register</button>
  </form>

</body>
</html>


<?php

// Database Configuration (Replace with your actual database credentials)
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // Validate the form input (Basic validation - Add more robust validation)
  $username = trim($_POST["username"]);
  $email = trim($_POST["email"]);
  $password = trim($_POST["password"]);

  // Validate required fields
  if (empty($username) || empty($email) || empty($password)) {
    $error = "All fields are required.";
  }

  // Validate email format
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $error = "Invalid email format.";
  }

  // Validate password length (adjust as needed)
  if (strlen($password) < 6) {
    $error = "Password must be at least 6 characters long.";
  }

  // Hash the password before storing it in the database
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
  $stmt->bind_param("ss", $username, $email, $hashed_password);

  // Execute the query
  if ($stmt->execute()) {
    $success = true;
    $success_message = "Registration successful.  Please check your email for verification.";
  } else {
    $error = "Registration failed.  " . $stmt->error;
  }

  // Close the statement and connection
  $stmt->close();
  $conn->close();
}
?>
