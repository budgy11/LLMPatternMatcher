    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required>

    <br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required>

    <br><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required>

    <br><br>

    <button type="submit">Register</button>
  </form>

  <p>Already have an account? <a href="login.php">Log in</a></p>  <!-- Link to login page -->

</body>
</html>


<?php

// Database connection details (Replace with your actual details)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_username";
$db_pass = "your_password";

// Check if the registration form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get form data
  $username = $_POST["username"];
  $email = $_POST["email"];
  $password = $_POST["password"];
  $confirm_password = $_POST["confirm_password"];


  // Validate the form data
  $errors = array();

  // Username validation
  if (empty($username)) {
    $errors["username"] = "Username cannot be empty.";
  } elseif (strlen($username) < 3) {
    $errors["username"] = "Username must be at least 3 characters long.";
  }

  // Email validation
  if (empty($email)) {
    $errors["email"] = "Email cannot be empty.";
  } else {
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $errors["email"] = "Invalid email format.";
    }
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
  } elseif ($password != $confirm_password) {
    $errors["confirm_password"] = "Passwords do not match.";
  }

  // If no errors, proceed with registration
  if (empty($errors)) {
    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Prepare the SQL query
    $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";

    // Prepare the statement
    $stmt = $conn->prepare($sql);

    // Bind the parameters
    $stmt->bind_param("ss", $username, $hashed_password);

    // Execute the statement
    if ($stmt->execute()) {
      // Registration successful
      echo "Registration successful! Please check your email for confirmation.";
      // You might want to redirect to a success page here.
    } else {
      // Registration failed
      echo "Registration failed. Error: " . $stmt->error;
    }

    // Close the statement
    $stmt->close();
  }
}

// Display the registration form
?>
