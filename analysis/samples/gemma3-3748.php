    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required><br><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required><br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required><br><br>

    <label for="confirm_password">Confirm Password:</label>
    <input type="password" id="confirm_password" name="confirm_password" required><br><br>

    <input type="submit" value="Register">
  </form>

</body>
</html>


<?php

// Database connection details (replace with your actual credentials)
$db_host = "localhost";
$db_user = "your_username";
$db_password = "your_password";
$db_name = "your_database_name";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Collect form data
  $username = $_POST["username"];
  $email = $_POST["email"];
  $password = $_POST["password"];
  $confirm_password = $_POST["confirm_password"];

  // Validate the data
  $errors = [];

  // Username validation
  if (empty($username)) {
    $errors[] = "Username cannot be empty.";
  }
  if (strlen($username) < 3) {
    $errors[] = "Username must be at least 3 characters long.";
  }
  if (preg_match('/^\s*$/', $username)) { // Check for whitespace-only username
     $errors[] = "Username cannot contain only whitespace.";
  }

  // Email validation
  if (empty($email)) {
    $errors[] = "Email cannot be empty.";
  }
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "Invalid email format.";
  }

  // Password validation
  if (empty($password)) {
    $errors[] = "Password cannot be empty.";
  }
  if (strlen($password) < 8) {
    $errors[] = "Password must be at least 8 characters long.";
  }
  if ($password != $confirm_password) {
    $errors[] = "Passwords do not match.";
  }

  // Check if there are any errors
  if (count($errors) > 0) {
    // Display errors (you might want to display them in a better way)
    echo "<h2>Error:</h2>";
    foreach ($errors as $error) {
      echo "<p>" . $error . "</p>";
    }
  } else {
    // Register the user
    try {
      // Connect to the database
      $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

      // Check connection
      if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
      }

      // Hash the password (important for security)
      $hashed_password = password_hash($password, PASSWORD_DEFAULT);

      // SQL query to insert the user into the database
      $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
      $stmt = $conn->prepare($sql);
      $stmt->bind_param("ss", $username, $hashed_password, $username); // Use username for email too
      $stmt->execute();

      // Check if the query executed successfully
      if ($stmt->affected_rows == 1) {
        // Registration successful
        echo "<h2>Registration Successful!</h2>";
        echo "<p>You have successfully created an account.  Please wait while your account is activated.</p>";
        // You might want to redirect the user to a login page or activate the account
        // header("Location: login.php"); // Replace login.php with your login page
      } else {
        throw new Exception("Registration failed.  Database error.");
      }

      $stmt->close();
      $conn->close();
    } catch (Exception $e) {
      echo "<h2>Error:</h2>";
      echo "<p>" . $e->getMessage() . "</p>";
    }
  }
}

// Form HTML
?>
