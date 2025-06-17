        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required><br><br>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required><br><br>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br><br>

        <input type="submit" value="Register">
    </form>

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
  // Get form data
  $username = $_POST["username"];
  $email = $_POST["email"];
  $password = $_POST["password"];

  // Validate the data (Crucial for security)
  $errors = [];
  if (empty($username)) {
    $errors[] = "Username cannot be empty.";
  }
  if (empty($email)) {
    $errors[] = "Email cannot be empty.";
  }
  if (empty($password)) {
    $errors[] = "Password cannot be empty.";
  }
  // Add more validation here, such as email format check
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "Invalid email format.";
  }
  // Password should have minimum length
  if (strlen($password) < 8) {
    $errors[] = "Password must be at least 8 characters long.";
  }


  // Check if there are any errors
  if (count($errors) > 0) {
    // Display error messages
    echo "<ul>";
    foreach ($errors as $error) {
      echo "<li>" . $error . "</li>";
    }
    echo "</ul>";
  } else {
    // Hash the password (VERY IMPORTANT for security)
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Prepare the SQL query
    $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";

    // Prepare the statement
    $stmt = $conn->prepare($sql);

    // Bind the parameters
    $stmt->bind_param("ss", $username, $hashed_password);

    // Execute the query
    if ($stmt->execute()) {
      // Registration successful
      echo "Registration successful!  Please check your email to activate your account.";
      // You might want to redirect to a confirmation page or login page here.
    } else {
      // Registration failed
      echo "Registration failed.  " . $conn->error;
    }

    // Close the statement
    $stmt->close();
  }
}

// Handle the form display
?>
