    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required>

    <br><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required>

    <br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required>

    <br><br>

    <label for="confirm_password">Confirm Password:</label>
    <input type="password" id="confirm_password" name="confirm_password" required>

    <br><br>

    <button type="submit">Register</button>
</form>

</body>
</html>


<?php

// Database configuration (replace with your actual credentials)
$db_host = 'localhost';
$db_user = 'your_db_user';
$db_password = 'your_db_password';
$db_name = 'your_db_name';

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // Get form data
  $username = $_POST["username"];
  $email = $_POST["email"];
  $password = $_POST["password"];
  $confirm_password = $_POST["confirm_password"];


  // Validate the form data
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
  if (empty($confirm_password)) {
    $errors[] = "Confirm Password cannot be empty.";
  }
  if ($password != $confirm_password) {
    $errors[] = "Passwords do not match.";
  }

  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "Invalid email format.";
  }

  // If there are errors, display them
  if (count($errors) > 0) {
    echo "<h2>Error:</h2><ul>";
    foreach ($errors as $error) {
      echo "<li>" . $error . "</li>";
    }
    echo "</ul>";
  } else {

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Connect to the database
    $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

    // Check connection
    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }

    // Prepare the SQL query
    $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";

    // Prepare the statement
    $stmt = $conn->prepare($sql);

    // Bind the parameters
    $stmt->bind_param("ss", $username, $hashed_password);

    // Execute the statement
    if ($stmt->execute()) {
      echo "<h2>Registration Successful!</h2>";
      echo "<p>You have successfully registered.  Please check your email for a confirmation link (if you are implementing email verification).</p>";
      // Optional: Redirect to a success page or login page
      // header("Location: login.php"); // Example: Redirect to login page
      exit;
    } else {
      echo "<h2>Registration Failed</h2>";
      echo "<p>" . $conn->error . "</p>";
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
  }
}
?>
