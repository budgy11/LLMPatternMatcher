
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

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required><br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required><br><br>

    <button type="submit">Register</button>
  </form>

</body>
</html>


<?php

// Database connection details (replace with your actual details)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_username";
$db_password = "your_password";

// Function to register a new user
function registerUser($username, $email, $password) {
  // 1. Validate Input (Crucial for security!)
  $username = trim($username); // Remove whitespace
  $email = trim($email);
  $password = trim($password);

  // Basic validation - improve these based on your requirements
  if (empty($username) || empty($email) || empty($password)) {
    return "Username, email, and password cannot be empty.";
  }
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    return "Invalid email format.";
  }
  if (strlen($password) < 8) {
    return "Password must be at least 8 characters long.";
  }


  // 2. Hash the Password (VERY IMPORTANT for security)
  $hashed_password = password_hash($password, PASSWORD_DEFAULT);


  // 3. Connect to the Database
  $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

  // Check connection
  if ($conn->connect_error) {
    return "Connection failed: " . $conn->connect_error;
  }

  // 4. Prepare the SQL Query
  $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
  $stmt = $conn->prepare($sql);

  if ($stmt === false) {
    return "SQL error preparing statement.";
  }

  // 5. Bind Parameters (Secure way to insert data)
  $stmt->bind_param("ss", $username, $email, $hashed_password);

  // 6. Execute the Query
  if (!$stmt->execute()) {
    return "Query failed: " . $stmt->error;
  }

  // 7. Close the Statement and Connection
  $stmt->close();
  $conn->close();

  return "User registered successfully!";
}

// Example Usage (Illustrative - Adapt to your form)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST["username"];
  $email = $_POST["email"];
  $password = $_POST["password"];

  $registration_result = registerUser($username, $email, $password);
  echo $registration_result;
}
?>
