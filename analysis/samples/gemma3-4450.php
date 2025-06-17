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

// Database configuration (Replace with your actual details)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_username";
$db_pass = "your_password";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Validate the input
  $username = trim($_POST["username"]);
  $email = trim($_POST["email"]);
  $password = trim($_POST["password"]);

  // Input validation (Crucial for security!)
  if (empty($username) || empty($email) || empty($password)) {
    $error_message = "All fields are required.";
  } elseif (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
    $error_message = "Invalid email format.";
  } elseif (strlen($password) < 6) {
    $error_message = "Password must be at least 6 characters long.";
  } else {
    // Hash the password (VERY IMPORTANT - Never store plain text passwords!)
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // SQL query to insert the user data
    $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";

    // Prepare the SQL statement
    $stmt = $conn->prepare($sql);

    // Bind the parameters
    $stmt->bind_param("ss", $username, $hashed_password);

    // Execute the query
    if ($stmt->execute()) {
      $success_message = "Registration successful! Please check your email to verify your account.";
    } else {
      $error_message = "An error occurred during registration: " . $stmt->error;
    }

    // Close the statement
    $stmt->close();
  }
}

// Connect to the database (Replace with your database connection)
$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

?>
