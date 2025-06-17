    Username: <input type="text" name="username" required>
    <br><br>
    Email: <input type="email" name="email" required>
    <br><br>
    Password: <input type="password" name="password" required>
    <br><br>
    <button type="submit">Register</button>
  </form>

</body>
</html>


<?php

// Database connection details (Replace with your actual credentials)
$db_host = "localhost";
$db_username = "your_username";
$db_password = "your_password";
$db_name = "your_database";

// Check if the registration form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Validate the form data (Crucial for security!)
  $username = trim($_POST["username"]);
  $email = trim($_POST["email"]);
  $password = trim($_POST["password"]);

  // Validate username (basic example - you should have more stringent rules)
  if (empty($username)) {
    $username_error = "Username cannot be empty.";
  } elseif (strlen($username) < 3) {
    $username_error = "Username must be at least 3 characters long.";
  }

  // Validate email (basic example)
  if (empty($email)) {
    $email_error = "Email cannot be empty.";
  } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $email_error = "Invalid email format.";
  }

  // Validate password (minimum length and complexity - improve this!)
  if (empty($password)) {
    $password_error = "Password cannot be empty.";
  } elseif (strlen($password) < 8) {
    $password_error = "Password must be at least 8 characters long.";
  }

  // Check if there are any validation errors
  if (!empty($username_error) || !empty($email_error) || !empty($password_error)) {
    $error = $username_error . " " . $email_error . " " . $password_error;
  } else {
    // Hash the password (IMPORTANT for security!)
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // SQL query to insert the user into the database
    $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql); // Use prepared statement
    $stmt->bind_param("sss", $username, $email, $hashed_password); // Use bind_param
    $stmt->execute();

    // Check if the query was successful
    if ($stmt->rowCount() == 1) {
      // Registration successful - redirect to a success page or log them in
      header("Location: success.php"); // Redirect to a success page
      exit();
    } else {
      $error = "Failed to register. Please try again.";
    }
  }
}
?>
