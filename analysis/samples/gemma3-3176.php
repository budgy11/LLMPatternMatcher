
<!DOCTYPE html>
<html>
<head>
  <title>User Registration</title>
</head>
<body>

  <h1>User Registration</h1>

  <form action="register.php" method="POST">
    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required><br><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required><br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required><br><br>

    <label for="confirm_password">Confirm Password:</label>
    <input type="password" id="confirm_password" name="confirm_password" required><br><br>

    <button type="submit">Register</button>
  </form>

</body>
</html>


<?php

// Database connection details (Replace with your actual credentials)
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "your_database_name";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // Validate and sanitize input (VERY IMPORTANT!)
  $firstname = htmlspecialchars($_POST["firstname"]);
  $lastname = htmlspecialchars($_POST["lastname"]);
  $email = htmlspecialchars($_POST["email"]);
  $password = htmlspecialchars($_POST["password"]);

  // Password validation (Add more robust validation as needed)
  if (empty($firstname) || empty($lastname) || empty($email) || empty($password)) {
    $error = "All fields are required.";
  } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $error = "Invalid email format.";
  } elseif (strlen($password) < 8) {
    $error = "Password must be at least 8 characters long.";
  }

  // Prepare the SQL query
  $sql = "INSERT INTO users (firstname, lastname, email, password) VALUES ('$firstname', '$lastname', '$email', '$password')";

  // Execute the query
  $result = mysqli_query($GLOBALS["servername"], $sql);

  // Check if the query was successful
  if ($result) {
    $success = "Registration successful. Please check your email to activate your account.";
  } else {
    $error = "Registration failed. " . mysqli_error($GLOBALS["servername"]);
  }
}
?>
