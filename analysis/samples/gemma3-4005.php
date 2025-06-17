        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required><br><br>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required><br><br>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br><br>

        <button type="submit">Register</button>
    </form>

</body>
</html>


<?php

// Database connection details (replace with your actual credentials)
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database";

// Check if the registration form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // Validate the form data
  $name = trim($_POST["name"]);
  $email = trim($_POST["email"]);
  $password = trim($_POST["password"]);

  // Validation Rules - IMPORTANT!  This is critical for security
  if (empty($name) || empty($email) || empty($password)) {
    $errors = "All fields are required.";
  } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors = "Invalid email format.";
  } elseif (strlen($password) < 8) {
    $errors = "Password must be at least 8 characters long.";
  } else {
    // Hash the password before storing it in the database
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Prepare the SQL query
    $sql = "INSERT INTO users (name, email, password) VALUES (?, ?, ?)";

    // Prepare the statement
    $stmt = $conn->prepare($sql);

    // Bind the parameters
    $stmt->bind_param("ss", $name, $hashed_password);

    // Execute the query
    if ($stmt->execute()) {
      $success = "Registration successful! You have been redirected.";
    } else {
      $errors = "Registration failed: " . $stmt->error;
    }
  }
}

// Display error or success messages
?>
