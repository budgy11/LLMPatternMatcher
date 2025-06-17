    <br><br>

    <button type="submit">Register</button>
  </form>

  <p>Already have an account? <a href="login.php">Log in</a></p>

</body>
</html>


<?php

// Database connection details (replace with your actual values)
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database";

// Check if the registration form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // Collect form data
  $username = $_POST["username"];
  $email = $_POST["email"];
  $password = $_POST["password"];
  $confirm_password = $_POST["confirm_password"];

  // Validate the form data (important for security and data integrity)
  $errors = [];

  // Username validation
  if (empty($username)) {
    $errors[] = "Username cannot be empty.";
  }

  // Email validation (basic check, use a robust library for real-world apps)
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
    // Display errors to the user
    echo "<h2>Errors:</h2>";
    foreach ($errors as $error) {
      echo "<p>" . $error . "</p>";
    }
  } else {
    // Prepare the data for insertion into the database
    $hashed_password = password_hash($password, PASSWORD_DEFAULT); // Securely hash the password

    // Construct the SQL query
    $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";

    // Prepare the statement
    $stmt = $conn->prepare($sql);

    // Bind the parameters
    $stmt->bind_param("ss", $username, $hashed_password);

    // Execute the statement
    if ($stmt->execute()) {
      // Registration successful
      echo "<h2>Registration successful!</h2>";
      echo "<p>You have successfully registered. Please <a href=\"login.php\">log in</a>.</p>";
      // Optionally redirect to a success page
      // header("Location: success.php");
      // exit();

    } else {
      // Something went wrong with the database insertion
      echo "<h2>Registration failed!</h2>";
      echo "<p>Error: " . print_r($stmt->error, true) . "</p>";
    }

    // Close the statement
    $stmt->close();
  }
}
?>
