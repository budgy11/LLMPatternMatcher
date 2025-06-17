    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required>

    <br><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required>

    <br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required>

    <br><br>

    <button type="submit">Register</button>
  </form>

</body>
</html>


<?php

// Database connection details (replace with your actual credentials)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_username";
$db_password = "your_password";

// Check if the registration form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get form data
  $username = $_POST["username"];
  $email = $_POST["email"];
  $password = $_POST["password"];

  // Validate the input (important for security)
  $errors = [];

  if (empty($username)) {
    $errors[] = "Username cannot be empty.";
  } elseif (strlen($username) < 3) {
    $errors[] = "Username must be at least 3 characters long.";
  }

  if (empty($email)) {
    $errors[] = "Email cannot be empty.";
  } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "Invalid email format.";
  }

  if (empty($password)) {
    $errors[] = "Password cannot be empty.";
  } elseif (strlen($password) < 8) {
    $errors[] = "Password must be at least 8 characters long.";
  }

  // If there are errors, display them
  if (!empty($errors)) {
    echo "<h2>Errors:</h2><ul>";
    foreach ($errors as $error) {
      echo "<li>" . $error . "</li>";
    }
    echo "</ul>";
  } else {
    // Hash the password (VERY IMPORTANT for security!)
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Prepare the SQL query
    $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";

    // Prepare the statement
    $stmt = $conn->prepare($sql); // Use $conn (your database connection)

    // Bind the parameters
    $stmt->bind_param("sss", $username, $email, $hashed_password); // "sss" means 3 string parameters

    // Execute the query
    if ($stmt->execute()) {
      // Registration successful!  Redirect to a success page or log them in
      echo "<h2>Registration Successful!</h2>";
      header("Location: success.php"); // Replace success.php with your success page
      exit();
    } else {
      // Handle errors if the query fails (e.g., duplicate username)
      echo "<h2>Error:</h2>" . $stmt->error;
    }

    // Close the statement
    $stmt->close();
  }
}
?>
