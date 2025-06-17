
    <br><br>

    <button type="submit">Register</button>
</form>

</body>
</html>


<?php

// Database configuration (Replace with your actual credentials)
$db_host = "localhost";
$db_username = "your_username";
$db_password = "your_password";
$db_name = "your_database";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Validate the form data
  $username = trim($_POST["username"]);
  $email = trim($_POST["email"]);
  $password = trim($_POST["password"]);

  // Input validation (Crucial for security)
  if (empty($username) || empty($email) || empty($password)) {
    $error_message = "All fields are required.";
  } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $error_message = "Invalid email format.";
  } elseif (strlen($password) < 8) {
    $error_message = "Password must be at least 8 characters long.";
  } else {
    // Hash the password (VERY IMPORTANT for security!)
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Connect to the database
    $conn = new mysqli($db_host, $db_username, $db_password, $db_name);

    // Check the connection
    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }

    // Prepare the SQL query (Use prepared statements to prevent SQL injection)
    $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
      // Bind the parameters
      $stmt->bind_param("ss", $username, $hashed_password);

      // Execute the query
      if ($stmt->execute()) {
        // Registration successful
        echo "Registration successful! Please check your email for verification instructions.";
        // You might want to redirect the user or show a success message
      } else {
        echo "Registration failed: " . $conn->error;
      }

      // Close the statement and connection
      $stmt->close();
      $conn->close();
    } else {
      echo "Error preparing statement.";
    }
  }
}
?>
