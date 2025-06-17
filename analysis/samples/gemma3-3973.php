    <br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" placeholder="Enter password">
    <br><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" placeholder="Enter email">
    <br><br>

    <button type="submit">Register</button>
  </form>

  <br><br>
  <a href="login.php">Already have an account?  Login here.</a>

</body>
</html>


<?php

// Database connection details (Replace with your actual credentials)
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database";

// Form handling (for simplicity, we'll directly use $_POST)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get form data
  $username = $_POST["username"];
  $email = $_POST["email"];
  $password = $_POST["password"];
  $confirm_password = $_POST["confirm_password"];

  // Validate input (VERY IMPORTANT!)
  $errors = [];

  // Username validation
  if (empty($username)) {
    $errors[] = "Username cannot be empty.";
  }
  if (strlen($username) < 3) {
    $errors[] = "Username must be at least 3 characters.";
  }
  if (strlen($username) > 20) {
    $errors[] = "Username cannot be longer than 20 characters.";
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
    $errors[] = "Password must be at least 8 characters.";
  }
  if ($password != $confirm_password) {
    $errors[] = "Passwords do not match.";
  }

  // No errors?  Proceed with registration
  if (empty($errors)) {
    // Database connection
    $conn = new mysqli($host, $username, $password, $database);

    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }

    // Prepare the SQL query
    $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
      // Bind parameters
      $stmt->bind_param("ss", $username, $password);

      // Execute the query
      if ($stmt->execute()) {
        // Registration successful
        echo "Registration successful! Please check your email for verification.";
        // You might want to redirect to a login page or another page
        // header("Location: login.php");
        // exit;
      } else {
        echo "Query failed: " . $conn->error;
      }

      $stmt->close();
    } else {
      echo "Query preparation failed: " . $conn->error;
    }

    $conn->close();
  } else {
    // Display validation errors
    echo "<ul>";
    foreach ($errors as $error) {
      echo "<li>" . $error . "</li>";
    }
    echo "</ul>";
  }
}

?>
