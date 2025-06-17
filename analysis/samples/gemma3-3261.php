    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required>
    <br><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required>
    <br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required>
    <br><br>

    <input type="submit" value="Register">
  </form>

</body>
</html>


<?php

// Database configuration (replace with your actual credentials)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_username";
$db_password = "your_password";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get form data
  $username = $_POST["username"];
  $email = $_POST["email"];
  $password = $_POST["password"];
  $confirm_password = $_POST["confirm_password"];

  // Validate input
  $errors = [];

  // Username Validation
  if (empty($username)) {
    $errors["username"] = "Username cannot be empty.";
  } elseif (strlen($username) < 3) {
    $errors["username"] = "Username must be at least 3 characters long.";
  }

  // Email Validation
  if (empty($email)) {
    $errors["email"] = "Email cannot be empty.";
  } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors["email"] = "Invalid email format.";
  }

  // Password Validation
  if (empty($password)) {
    $errors["password"] = "Password cannot be empty.";
  } elseif (strlen($password) < 8) {
    $errors["password"] = "Password must be at least 8 characters long.";
  } elseif ($password != $confirm_password) {
    $errors["password"] = "Passwords do not match.";
  }

  // If there are no errors, proceed with registration
  if (empty($errors)) {
    // Sanitize input (important for security)
    $username = htmlspecialchars($username);
    $email = htmlspecialchars($email);
    $password = password_hash($password, PASSWORD_DEFAULT); // Hash the password for security

    // Connect to the database
    $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

    // Check connection
    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }

    // Prepare the SQL query
    $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";

    // Prepare the statement
    $stmt = $conn->prepare($sql);

    // Bind parameters
    $stmt->bind_param("ss", $username, $email, $password);

    // Execute the query
    if ($stmt->execute()) {
      // Registration successful
      echo "Registration successful! You have been logged in.";
      // You might want to redirect to a success page or perform other actions
      header("Location: success.php"); // Assuming you have a success.php page
      exit();
    } else {
      // Registration failed
      echo "Registration failed: " . $conn->error;
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
  }
}
?>
