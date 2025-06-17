
<!DOCTYPE html>
<html>
<head>
  <title>User Registration</title>
</head>
<body>

  <h1>User Registration</h1>

  <form method="post" action="">
    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required><br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required><br><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required><br><br>

    <input type="submit" value="Register">
  </form>

</body>
</html>


<?php

// Database configuration (Replace with your actual credentials)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_database_user";
$db_password = "your_database_password";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // Validate and sanitize the input
  $username = filter_input(INPUT_POST, "username", FILTER_SANITIZE_STRING);
  $email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL);
  $password = filter_input(INPUT_POST, "password", FILTER_SANITIZE_STRING);


  // Check if username and email are empty
  if (empty($username) || empty($email) || empty($password)) {
    $error_message = "All fields are required.";
  } else {
    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $error_message = "Invalid email format.";
    } else {

      // Hash the password using password_hash() - IMPORTANT for security!
      $hashed_password = password_hash($password, PASSWORD_DEFAULT);

      // SQL query to insert the new user into the database
      $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";

      // Prepare the statement (prevents SQL injection)
      $stmt = $conn->prepare($sql);

      // Bind the parameters
      $stmt->bind_param("ss", $username, $email, $hashed_password);

      // Execute the statement
      if ($stmt->execute()) {
        $success_message = "Registration successful! Please check your email to verify your account.";
        // Optionally, redirect to a welcome page or login page
        // header("Location: welcome.php");
        // exit();
      } else {
        $error_message = "Error registering user. " . $stmt->error;
      }
    }
  }
}

// Connect to the database (replace with your connection details)
$conn = new mysqli($db_host, $db_user, $db_password, $db_name);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

?>
