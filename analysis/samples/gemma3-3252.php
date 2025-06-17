
  <form action="register.php" method="post">
    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required>

    <br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required>

    <br><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required>

    <br><br>

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
  // Validate the form data
  $username = trim($_POST["username"]);
  $email = trim($_POST["email"]);
  $password = trim($_POST["password"]);

  // Validate username
  if (empty($username)) {
    $username_error = "Username cannot be empty.";
  } elseif (strlen($username) < 3) {
    $username_error = "Username must be at least 3 characters long.";
  } else {
    $username_error = ""; // Clear any previous errors
  }

  // Validate email
  if (empty($email)) {
    $email_error = "Email cannot be empty.";
  } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $email_error = "Invalid email address.";
  } else {
    $email_error = "";
  }

  // Validate password
  if (empty($password)) {
    $password_error = "Password cannot be empty.";
  } elseif (strlen($password) < 8) {
    $password_error = "Password must be at least 8 characters long.";
  } else {
    $password_error = "";
  }

  // If no errors, proceed with registration
  if ($username_error == "" && $email_error == "" && $password_error == "") {
    // Hash the password (IMPORTANT for security)
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Connect to the database
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }

    // Prepare and execute the SQL query
    $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $username, $email, $hashed_password);
    $stmt->execute();

    // Success message
    $registration_message = "Registration successful! Please log in.";

    // Close the statement and connection
    $stmt->close();
    $conn->close();
  }
}
?>
