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

// Include the database connection file (replace 'db_config.php' with your actual file)
require_once 'db_config.php';

// Check if the registration form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // Validate the input data
  $username = trim($_POST["username"]);
  $email = trim($_POST["email"]);
  $password = trim($_POST["password"]);
  $confirm_password = trim($_POST["confirm_password"]);

  // Input Validation - Crucial for security
  if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
    $error_message = "All fields are required.";
  } elseif (strlen($username) < 3) {
    $error_message = "Username must be at least 3 characters long.";
  } elseif (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
    $error_message = "Invalid email format.";
  } elseif ($password != $confirm_password) {
    $error_message = "Passwords do not match.";
  }

  // If there are no errors, proceed with registration
  if (empty($error_message)) {
    // Hash the password - VERY IMPORTANT for security!
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Prepare the SQL query
    $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";

    // Prepare the statement
    $stmt = $conn->prepare($sql);

    // Bind the parameters
    $stmt->bind_param("sss", $username, $email, $hashed_password);

    // Execute the query
    if ($stmt->execute()) {
      // Registration successful
      $success_message = "Registration successful! Please check your email to verify your account.";
    } else {
      // Registration failed
      $error_message = "Registration failed.  Error: " . $stmt->error; //Display specific error
    }
  }
}
?>
