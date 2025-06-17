    <br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" placeholder="Enter password">
    <br><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" placeholder="Enter email">
    <br><br>

    <button type="submit">Register</button>
  </form>

</body>
</html>


<?php

// Database configuration (Replace with your actual database details)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_username";
$db_password = "your_password";


// Check if the registration form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Validate the form data
  $username = trim($_POST["username"]);
  $email = trim($_POST["email"]);
  $password = trim($_POST["password"]);
  $confirm_password = trim($_POST["confirm_password"]);

  // Validation checks
  if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
    $error_message = "All fields are required.";
  } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $error_message = "Invalid email format.";
  } elseif ($password != $confirm_password) {
    $error_message = "Passwords do not match.";
  }  else {
    // Password hashing (Important for security!)
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Prepare the SQL query
    $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";

    // Prepare the statement
    $stmt = $conn->prepare($sql);  // Assuming $conn is your database connection object

    // Bind the parameters
    $stmt->bind_param("sss", $username, $email, $hashed_password);  // 's' indicates a string

    // Execute the query
    if ($stmt->execute()) {
      $success_message = "Registration successful.  Please check your email to activate your account.";
    } else {
      $error_message = "Registration failed. " . $stmt->error;
    }
  }
}

// Start the session (if not already started)
if (!session_id()) {
  session_start();
}

// Render the registration form
?>
