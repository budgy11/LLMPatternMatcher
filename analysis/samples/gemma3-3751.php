
<!DOCTYPE html>
<html>
<head>
  <title>Registration</title>
</head>
<body>

  <h2>Register</h2>

  <form method="post" action="register.php">
    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required><br><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required><br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required><br><br>

    <button type="submit">Register</button>
  </form>

</body>
</html>


<?php

// Database connection details (Replace with your actual credentials)
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // Sanitize the input
  $firstname = filter_input(INPUT_POST, "firstname", FILTER_SANITIZE_STRING);
  $lastname = filter_input(INPUT_POST, "lastname", FILTER_SANITIZE_STRING);
  $email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL);
  $password = filter_input(INPUT_POST, "password", FILTER_SANITIZE_STRING);

  // Validate the email format
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $email_error = "Invalid email format.";
  }

  // Validate the password (Simple example - you'll want a stronger validation)
  if (strlen($password) < 8) {
    $password_error = "Password must be at least 8 characters long.";
  }

  // Hash the password (VERY IMPORTANT for security!)
  $hashed_password = password_hash($password, PASSWORD_DEFAULT);

  // Check if all validation passes
  if (empty($firstname) || empty($lastname) || empty($email) || empty($password)) {
    $error = "Please fill in all fields.";
  } else {
    // Prepare the SQL query
    $sql = "INSERT INTO users (firstname, lastname, email, password) VALUES (?, ?, ?, ?)";

    // Prepare the statement
    $stmt = $conn->prepare($sql);

    // Bind the parameters
    $stmt->bind_param("sss", $firstname, $lastname, $email, $hashed_password);

    // Execute the query
    if ($stmt->execute()) {
      // Registration successful
      echo "Registration successful! Please check your email to activate your account.";
      // You might redirect to a confirmation page here
    } else {
      // Registration failed
      echo "Registration failed. Error: " . $stmt->error;
    }
  }
}
?>
