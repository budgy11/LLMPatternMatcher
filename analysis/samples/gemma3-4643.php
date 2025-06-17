    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required><br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required><br><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required><br><br>

    <button type="submit">Register</button>
  </form>

</body>
</html>


<?php

// Database connection details (replace with your actual values)
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "your_database_name";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Validate the input
  $username = trim($_POST["username"]);
  $email = trim($_POST["email"]);
  $password = trim($_POST["password"]);

  // Sanitize input (important for security)
  $username = filter_var($username, FILTER_SANITIZE_STRING);
  $email = filter_var($email, FILTER_SANITIZE_EMAIL);
  $password = filter_var($password, FILTER_SANITIZE_STRING);

  // Check if required fields are filled
  if (empty($username) || empty($email) || empty($password)) {
    $error = "All fields are required.";
  } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $error = "Invalid email format.";
  } else if (!preg_match("/^[a-zA-Z0-9]+$/", $username)) {
    $error = "Username must contain only letters and numbers.";
  } else if (strlen($password) < 8) {
    $error = "Password must be at least 8 characters long.";
  }

  // If no errors, proceed with registration
  if (empty($error)) {
    // Hash the password (VERY IMPORTANT - never store plain text passwords)
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Connect to the database
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }

    // Prepare the SQL query
    $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";

    // Prepare the statement
    $stmt = $conn->prepare($sql);

    // Bind the parameters
    $stmt->bind_param("ss", $username, $hashed_password);

    // Execute the query
    if ($stmt->execute()) {
      // Registration successful
      echo "Registration successful!  Please check your email for a verification link.";
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
