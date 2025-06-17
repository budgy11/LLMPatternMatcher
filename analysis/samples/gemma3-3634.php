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

// Database configuration (replace with your actual credentials)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_username";
$db_password = "your_password";

// Check if the registration form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Validate input data
  $username = filter_input(INPUT_POST, "username", FILTER_SANITIZE_STRING);
  $email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL);
  $password = filter_input(INPUT_POST, "password", FILTER_SANITIZE_STRING);


  // Password validation (basic - consider stronger validation)
  if (empty($username) || empty($email) || empty($password)) {
    $error_message = "All fields are required.";
  } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $error_message = "Invalid email format.";
  } elseif (strlen($password) < 8) {
    $error_message = "Password must be at least 8 characters long.";
  } else {
    // Hash the password before storing it in the database
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Prepare the SQL query
    $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";

    // Prepare and execute the query
    $stmt = $db->prepare($sql);  // Assuming you have a database connection object named $db
    $stmt->bind_param("sss", $username, $email, $hashed_password);  // "sss" indicates 3 string parameters
    $stmt->execute();

    // Check if the query executed successfully
    if ($stmt->errorCode() !== 0) {
      $error_message = "Error creating user: " . $stmt->error;
    } else {
      $error_message = "User registered successfully!";
    }

    // Close the statement
    $stmt->close();
    }
}

// Display error message if any
if (isset($error_message)) {
  echo "<p style='color: red;'>$error_message</p>";
}

?>
