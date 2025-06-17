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

// Database configuration (replace with your actual database credentials)
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Sanitize the input data
  $firstname = filter_input(INPUT_POST, "firstname", FILTER_SANITIZE_STRING);
  $lastname = filter_input(INPUT_POST, "lastname", FILTER_SANITIZE_STRING);
  $email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL);
  $password = filter_input(INPUT_POST, "password", FILTER_SANITIZE_STRING);

  // Validate the email format
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $error_message = "Invalid email format.";
  }
  // Validate the password (e.g., minimum length)
  if (strlen($password) < 8) {
    $error_message = "Password must be at least 8 characters long.";
  }

  // Hash the password (Important for security!)
  $hashed_password = password_hash($password, PASSWORD_DEFAULT);

  // Connect to the database
  $conn = new mysqli($host, $username, $password, $database);

  // Check the connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // Prepare the SQL query
  $sql = "INSERT INTO users (firstname, lastname, email, password) VALUES (?, ?, ?, ?)";

  // Prepare the statement
  $stmt = $conn->prepare($sql);

  // Bind the parameters
  $stmt->bind_param("ssis", $firstname, $lastname, $hashed_password);

  // Execute the query
  if ($stmt->execute()) {
    // Registration successful
    echo "Registration successful! You have been redirected.";
    // Redirect to a welcome page or login page
    header("Location: welcome.php"); // Replace with your desired redirect URL
    exit();
  } else {
    // Registration failed
    echo "Registration failed: " . $stmt->error;
  }

  // Close the statement and connection
  $stmt->close();
  $conn->close();
}
?>
