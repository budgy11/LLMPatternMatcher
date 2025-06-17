    Username: <input type="text" name="username" required><br><br>
    Password: <input type="password" name="password" required><br><br>
    Email: <input type="email" name="email" required><br><br>
    <input type="submit" value="Register">
  </form>

</body>
</html>


<?php

// Database connection details (Replace with your actual credentials)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_db_user";
$db_password = "your_db_password";

// Check if the registration form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Validate the form data (Crucial for security)
  $username = trim($_POST["username"]);
  $email = trim($_POST["email"]);
  $password = trim($_POST["password"]);

  // Basic validation (You should add more robust validation here)
  if (empty($username) || empty($email) || empty($password)) {
    $error_message = "All fields are required.";
  } elseif (
    !preg_match("/^[a-zA-Z0-9]+$/", $username)  // Allow only alphanumeric characters
  ) {
    $error_message = "Username must contain only alphanumeric characters.";
  } elseif (
    !filter_var($email, FILTER_VALIDATE_EMAIL)
  ) {
    $error_message = "Invalid email address.";
  } elseif (
    strlen($password) < 8
  ) {
    $error_message = "Password must be at least 8 characters long.";
  } else {
    // Hash the password (VERY IMPORTANT - never store passwords in plain text)
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Prepare the SQL query
    $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";

    // Prepare the statement
    $stmt = $conn->prepare($sql); // Assuming $conn is your database connection

    // Bind the parameters
    $stmt->bind_param("ss", $username, $hashed_password);

    // Execute the query
    if ($stmt->execute()) {
      // Registration successful
      echo "Registration successful! You have been redirected.";
      // You can redirect to a welcome page or login page here.
      header("Location: login.php"); // Example redirect
      exit();
    } else {
      // Registration failed
      echo "Registration failed: " . $stmt->error;
    }
  }

  // Display the error message if any
  if (!empty($error_message)) {
    echo "<p style='color: red;'>Error: " . $error_message . "</p>";
  }
}
?>
