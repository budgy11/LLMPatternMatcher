
<!DOCTYPE html>
<html>
<head>
  <title>User Registration</title>
</head>
<body>

  <h1>Register</h1>

  <form action="register.php" method="post">
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

// Database connection details - Replace with your actual values
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "your_database_name";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // Collect form data
  $firstname = $_POST["firstname"];
  $lastname = $_POST["lastname"];
  $email = $_POST["email"];
  $password = $_POST["password"];
  $confirm_password = $_POST["confirm_password"];

  // Validate the data
  $errors = [];

  // Check if required fields are empty
  if (empty($firstname) || empty($lastname) || empty($email) || empty($password) || empty($confirm_password)) {
    $errors[] = "All fields are required.";
  }

  // Validate email format
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "Invalid email format.";
  }

  // Validate password strength (basic)
  if (strlen($password) < 8) {
    $errors[] = "Password must be at least 8 characters long.";
  }

  // Check if passwords match
  if ($password != $confirm_password) {
    $errors[] = "Passwords must match.";
  }

  // If there are no errors, proceed with registration
  if (empty($errors)) {
    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Prepare the SQL query
    $sql = "INSERT INTO users (firstname, lastname, email, password) VALUES (?, ?, ?, ?)";

    // Prepare the statement
    $stmt = $conn->prepare($sql);

    // Bind parameters
    $stmt->bind_param("sss", $firstname, $lastname, $hashed_password);

    // Execute the query
    if ($stmt->execute()) {
      // Registration successful
      echo "Registration successful! You have been redirected.";
      // Optionally, redirect to a welcome page or login page
      header("Location: welcome.php"); // Replace welcome.php with your welcome page
      exit();
    } else {
      // Registration failed
      echo "Registration failed. " . $conn->error;
    }
  }
}
?>
