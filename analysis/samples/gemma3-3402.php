    Username: <input type="text" name="username" required><br><br>
    Email: <input type="email" name="email" required><br><br>
    Password: <input type="password" name="password" required><br><br>
    <input type="submit" value="Register">
  </form>

</body>
</html>


<?php

// Database connection details (Replace with your actual credentials)
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // Validate input (Important for security - more robust validation is recommended)
  $username = trim($_POST["username"]);
  $email = trim($_POST["email"]);
  $password = trim($_POST["password"]);

  // Basic validation (you should add more comprehensive validation)
  if (empty($username) || empty($email) || empty($password)) {
    $errors = "All fields are required.";
  } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors = "Invalid email format.";
  } elseif (strlen($password) < 8) {
    $errors = "Password must be at least 8 characters long.";
  }

  // If no errors, proceed with registration
  if (empty($errors)) {

    // Prepare the SQL query to prevent SQL injection
    $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql); // Assuming $conn is your database connection

    if ($stmt) {
      // Hash the password - VERY IMPORTANT FOR SECURITY
      $hashed_password = password_hash($password, PASSWORD_DEFAULT);

      // Bind parameters
      $stmt->bind_param("ss", $username, $hashed_password);

      // Execute the query
      if ($stmt->execute()) {
        // Registration successful
        echo "Registration successful! Please check your email to verify your account.";
        // Redirect to a confirmation page or login page
        header("Location: confirmation.php");
        exit();
      } else {
        // Query failed
        echo "Error: " . $stmt->error;
      }
    } else {
      echo "Error preparing statement.";
    }

    $stmt->close();
  }
}
?>
