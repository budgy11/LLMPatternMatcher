    <br><br>

    <button type="submit">Register</button>
  </form>

  <p>Already have an account? <a href="login.php">Login</a></p>

</body>
</html>


<?php

// Database credentials - Replace with your actual credentials
$db_host = "localhost";
$db_user = "your_username";
$db_password = "your_password";
$db_name = "your_database_name";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // Get form data
  $username = $_POST["username"];
  $email = $_POST["email"];
  $password = $_POST["password"];

  // Validate input (IMPORTANT - Add more robust validation here!)
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

  if (strlen($username) < 3) {
    $errors[] = "Username must be at least 3 characters long.";
  }

  // Simple email validation (consider a more comprehensive regex)
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "Invalid email format.";
  }
  
  if (count($errors)) {
    // Display errors
    echo "<h2>Error:</h2>";
    echo "<ol>";
    foreach ($errors as $error) {
      echo "<li>" . $error . "</li>";
    }
    echo "</ol>";
  } else {
    // Hash the password (VERY IMPORTANT for security!)
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Prepare the SQL query
    $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";

    // Prepare and execute the query
    $stmt = $db->prepare($sql);
    $stmt->bind_param("sss", $username, $email, $hashed_password);  // "s" for string
    $stmt->execute();

    // Check if the query was successful
    if ($stmt->affected_rows > 0) {
      // User registered successfully
      echo "<h2>Registration successful!</h2>";
      echo "<p>You have successfully registered.  Please check your email to verify your account.</p>";
    } else {
      echo "<h2>Error:</h2>";
      echo "<p>Failed to register.  Please try again.</p>";
    }

    $stmt->close();
  }
}
?>
