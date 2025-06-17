    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required><br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required><br><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required><br><br>

    <input type="submit" value="Register">
  </form>

</body>
</html>


<?php

// Database connection details (replace with your actual details)
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "your_database_name";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Validate the input data
  $name = trim($_POST["name"]);
  $email = trim($_POST["email"]);
  $password = trim($_POST["password"]);

  // Sanitize the input data (important for security)
  $name = filter_var($name, FILTER_SANITIZE_STRING);
  $email = filter_var($email, FILTER_SANITIZE_EMAIL);
  $password = filter_var($password, FILTER_SANITIZE_STRING);


  // Perform validation
  if (empty($name) || empty($email) || empty($password)) {
    $errors = "All fields are required.";
  } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors = "Invalid email format.";
  } elseif (strlen($password) < 8) {
    $errors = "Password must be at least 8 characters long.";
  }
   else {
    // Hash the password (essential for security)
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Attempt to connect to the database
    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }

    // Prepare the SQL query
    $sql = "INSERT INTO users (name, email, password) VALUES (?, ?, ?)";

    // Prepare the statement
    $stmt = $conn->prepare($sql);

    // Bind the parameters
    $stmt->bind_param("ss", $name, $hashed_password);

    // Execute the query
    if ($stmt->execute()) {
      $success = true;
      $success_message = "Registration successful! You have been redirected.";
    } else {
      $errors = "Registration failed: " . $stmt->error;
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
  }
}

?>
