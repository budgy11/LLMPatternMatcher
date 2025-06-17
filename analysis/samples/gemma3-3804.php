    <label for="username">Username:</label>
    <input type="text" id="username" name="username" placeholder="Enter username" required>

    <br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" placeholder="Enter password" required>

    <br><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" placeholder="Enter email" required>

    <br><br>

    <button type="submit">Register</button>
  </form>

  <p>Already have an account? <a href="login.php">Log in</a></p> <!-- Link to login page -->

</body>
</html>


<?php

// Database connection details (Replace with your actual credentials)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_username";
$db_password = "your_password";

// Form handling (Handle POST request)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get form data
  $username = $_POST["username"];
  $email = $_POST["email"];
  $password = $_POST["password"];
  $confirm_password = $_POST["confirm_password"];

  // Validate form data
  $errors = [];

  // Username validation
  if (empty($username)) {
    $errors[] = "Username cannot be empty.";
  } elseif (strlen($username) < 3) {
    $errors[] = "Username must be at least 3 characters long.";
  }

  // Email validation
  if (empty($email)) {
    $errors[] = "Email cannot be empty.";
  } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "Invalid email format.";
  }

  // Password validation
  if (empty($password)) {
    $errors[] = "Password cannot be empty.";
  } elseif (strlen($password) < 8) {
    $errors[] = "Password must be at least 8 characters long.";
  }

  // Confirm password validation
  if (empty($confirm_password)) {
    $errors[] = "Confirm Password cannot be empty.";
  } elseif ($password != $confirm_password) {
    $errors[] = "Passwords do not match.";
  }

  // Check if there are any errors
  if (count($errors) > 0) {
    // Display error messages
    echo "<h2>Error:</h2>";
    foreach ($errors as $error) {
      echo "<p>" . $error . "</p>";
    }
  } else {
    // Prepare data for insertion
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // SQL query to insert user data
    $sql = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$hashed_password')";

    // Execute the query
    $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }

    if ($conn->query($sql)) {
      echo "<h2>Registration successful!</h2>";
      echo "<p>You have successfully registered. Please <a href=\"login.php\">login</a>.</p>";
    } else {
      echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
  }
}
?>
