
<!DOCTYPE html>
<html>
<head>
  <title>User Registration</title>
</head>
<body>

  <h2>User Registration</h2>

  <form method="post" action="">
    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required><br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required><br><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required><br><br>

    <button type="submit">Register</button>
  </form>

</body>
</html>


<?php

// Database configuration (replace with your actual credentials)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_db_user";
$db_password = "your_db_password";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Sanitize and validate the input data
  $username = trim($_POST["username"]);
  $password = trim($_POST["password"]);
  $email = trim($_POST["email"]);

  // Validate input (important for security)
  if (empty($username) || empty($password) || empty($email)) {
    $errors = "All fields are required.";
  } elseif (strlen($username) < 3) {
    $errors = "Username must be at least 3 characters.";
  } elseif (preg_match("/@/", $email)) {
    $errors = "Invalid email format.";
  }  else {
    // Hash the password before storing it in the database
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Connect to the database
    $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

    // Check the connection
    if ($conn->connect_error) {
      $errors = "Connection failed: " . $conn->connect_error;
    } else {
      // Prepare and execute the SQL query
      $sql = "INSERT INTO users (username, password, email) VALUES (?, ?, ?)";
      $stmt = $conn->prepare($sql);
      $stmt->bind_param("sss", $username, $hashed_password, $email);
      $stmt->execute();

      // Check if the query was successful
      if ($stmt->affected_rows == 0) {
        $errors = "Registration failed.  Please try again.";
      }

      // Close the statement and connection
      $stmt->close();
      $conn->close();

      // Redirect to a success page (optional)
      if ($stmt->affected_rows > 0) {
        header("Location: success.php?message=Registration successful!");
        exit();
      }
    }
  }
}
?>
