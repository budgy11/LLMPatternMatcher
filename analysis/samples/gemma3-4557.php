
<!DOCTYPE html>
<html>
<head>
  <title>User Registration</title>
</head>
<body>

  <h2>User Registration</h2>

  <form method="post" action="">
    <label for="name">Name:</label>
    <input type="text" id="name" name="name" required><br><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required><br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required><br><br>

    <input type="submit" value="Register">
  </form>

</body>
</html>


<?php

// Database Configuration (Replace with your actual credentials)
$db_host = "localhost";
$db_user = "your_db_user";
$db_password = "your_db_password";
$db_name = "your_db_name";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Sanitize input data
  $username = filter_input(INPUT_POST, "username", FILTER_SANITIZE_STRING);
  $email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL);
  $password = filter_input(INPUT_POST, "password", FILTER_SANITIZE_STRING);

  // Validate input data
  $username = trim($username);
  $email = trim($email);
  $password = trim($password);

  // Check if required fields are filled
  if (empty($username) || empty($email) || empty($password)) {
    $error = "All fields are required.";
  } elseif (
    !filter_var($email, FILTER_VALIDATE_EMAIL)
  ) {
    $error = "Invalid email format.";
  } elseif (
    // Add more password complexity validation here if needed
    strlen($password) < 6
  ) {
    $error = "Password must be at least 6 characters long.";
  } else {
    // Hash the password using password_hash()
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Prepare the SQL query
    $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";

    // Prepare and execute the query
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $username, $email, $hashed_password);
    $stmt->execute();

    // Check if the query was successful
    if ($stmt->errno) {
      $error = "Error: " . $stmt->error;
    } else {
      // Redirect to a success page or display a success message
      header("Location: success.php");
      exit(); // Important to stop further execution
    }
  }
}

// Display the registration form
?>
