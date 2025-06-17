
  <form action="registration.php" method="POST">
    <label for="firstname">First Name:</label>
    <input type="text" id="firstname" name="firstname" required>

    <br><br>

    <label for="lastname">Last Name:</label>
    <input type="text" id="lastname" name="lastname" required>

    <br><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required>

    <br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required>

    <br><br>

    <input type="submit" value="Register">
  </form>

  <p>Already have an account? <a href="login.php">Login</a></p>

</body>
</html>


<?php

// Database configuration (Replace with your actual database credentials)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_username";
$db_password = "your_password";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // Collect data from the form
  $username = $_POST["username"];
  $email = $_POST["email"];
  $password = $_POST["password"];
  $confirm_password = $_POST["confirm_password"];

  // Validate the data (Crucial for security)
  $errors = [];

  // Username validation
  if (empty($username)) {
    $errors["username"] = "Username cannot be empty.";
  } elseif (strlen($username) < 3) {
    $errors["username"] = "Username must be at least 3 characters long.";
  }

  // Email validation
  if (empty($email)) {
    $errors["email"] = "Email cannot be empty.";
  } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors["email"] = "Invalid email format.";
  }

  // Password validation
  if (empty($password)) {
    $errors["password"] = "Password cannot be empty.";
  } elseif (strlen($password) < 8) {
    $errors["password"] = "Password must be at least 8 characters long.";
  }

  // Confirm password validation
  if (empty($confirm_password)) {
    $errors["confirm_password"] = "Confirm password cannot be empty.";
  } elseif ($password !== $confirm_password) {
    $errors["confirm_password"] = "Passwords do not match.";
  }

  // If there are no errors, proceed with registration
  if (empty($errors)) {
    // Hash the password (VERY IMPORTANT for security!)
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Connect to the database
    $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

    // Check the connection
    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }

    // Prepare the SQL query
    $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";

    // Prepare the statement (Recommended for security)
    $stmt = $conn->prepare($sql);

    // Bind the parameters
    $stmt->bind_param("ss", $username, $hashed_password);

    // Execute the statement
    if ($stmt->execute()) {
      // Registration successful
      echo "Registration successful! Please check your email for confirmation instructions.";
    } else {
      // Registration failed
      echo "Registration failed: " . $stmt->error;
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();

  } else {
    // Display the form with error messages
    echo "<h2>Error:</h2>";
    echo "<ol>";
    foreach ($errors as $error) {
      echo "<li>" . $error . "</li>";
    }
    echo "</ol>";
  }
}
?>
