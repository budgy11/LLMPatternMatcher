
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

// Database connection details (Replace with your actual credentials)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_database_user";
$db_password = "your_database_password";

// Function to register a new user
function registerUser($username, $email, $password) {
  // 1. Database Connection
  $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // 2. Input Validation and Sanitization (Crucial for Security!)
  $username = trim($username);
  $email = filter_var(strip_tags($email), FILTER_SANITIZE_EMAIL);
  $password = trim($password);

  // Check for empty fields
  if (empty($username) || empty($email) || empty($password)) {
    return "All fields are required.";
  }

  // Password validation (basic example - enhance this!)
  if (strlen($password) < 8) {
    return "Password must be at least 8 characters long.";
  }

  // 3. Hash the password (VERY IMPORTANT for security)
  $hashed_password = password_hash($password, PASSWORD_DEFAULT);


  // 4.  SQL Query (Prepared statement is preferred for security - see note below)
  $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("ss", $username, $email, $hashed_password); // "ss" means string, string, string
  if ($stmt->execute()) {
    $stmt->close();
    return "Registration successful!  Please check your email to verify.";
  } else {
    $error = $stmt->error;
    $stmt->close();
    return "Registration failed: " . $error;
  }
}

// --- Example Usage (Handle Form Submission) ---

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get form data
  $username = $_POST["username"];
  $email = $_POST["email"];
  $password = $_POST["password"];

  // Call the registration function
  $registration_result = registerUser($username, $email, $password);

  // Display the result
  echo "<p>" . $registration_result . "</p>";
}

?>
