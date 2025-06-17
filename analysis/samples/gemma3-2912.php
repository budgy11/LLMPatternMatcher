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

// Database credentials (replace with your actual credentials)
$db_host = "localhost";
$db_user = "your_username";
$db_password = "your_password";
$db_name = "your_database";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // Get form data
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
  // You can add more username validation here (e.g., length, special characters)

  if (empty($email)) {
    $errors[] = "Email cannot be empty.";
  }
  // Email validation (basic)
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "Invalid email format.";
  }

  if (empty($password)) {
    $errors[] = "Password cannot be empty.";
  }
  if (empty($confirm_password)) {
    $errors[] = "Confirm password cannot be empty.";
  }

  if ($password != $confirm_password) {
    $errors[] = "Passwords do not match.";
  }

  // Password validation (basic - you should use a more robust method)
  //  Consider using password_verify() with a hashed password in your database
  //  for security reasons.  This is just an example.
  if (strlen($password) < 8) {
      $errors[] = "Password must be at least 8 characters long.";
  }

  // Check for errors
  if (count($errors) > 0) {
    // Display errors (e.g., using a form with error messages)
    echo "<h2>Error:</h2>";
    foreach ($errors as $error) {
      echo "<p>" . $error . "</p>";
    }
  } else {
    //  Hash the password (important for security!)
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Connect to the database
    $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

    // Check the connection
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
      echo "<h2>Registration successful!</h2>";
      echo "<p>You have successfully registered.</p>";
      // Redirect to a success page or login page
      header("Location: login.php"); //  Replace login.php with your login page
      exit();
    } else {
      echo "<h2>Registration failed!</h2>";
      echo "<p>" . $conn->error . "</p>";
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
  }
}
?>
