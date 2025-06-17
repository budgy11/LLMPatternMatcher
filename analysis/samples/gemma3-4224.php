
</body>
</html>


<?php

// Database configuration (replace with your actual credentials)
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // Sanitize and validate input data
  $username = trim($_POST["username"]);
  $email = trim($_POST["email"]);
  $password = trim($_POST["password"]);

  // Validation (add more robust validation as needed)
  if (empty($username) || empty($email) || empty($password)) {
    $error_message = "All fields are required.";
  } elseif (
    !preg_match("/^[a-zA-Z0-9._-]+$/", $username) || // Username validation
    !preg_match("/^[^\s@]+@[^\s@]+\.[^\s@]+$/", $email) || // Email validation
    $password < 6 // Password minimum length
  ) {
    $error_message = "Invalid input. Please check your username, email, and password.";
  } else {

    // Hash the password (VERY IMPORTANT for security!)
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Connect to the database
    $conn = new mysqli($host, $username, $password, $database);

    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }

    // Prepare and execute the SQL query
    $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $username, $email, $hashed_password);
    $stmt->execute();

    // Close the statement and connection
    $stmt->close();
    $conn->close();

    // Display success message
    echo "<p style='color:green;'>Registration successful! You have been redirected.</p>";
    // You can also redirect the user to a welcome page or login page here.
  }
}
?>
