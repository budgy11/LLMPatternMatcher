
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

        <button type="submit">Register</button>
    </form>

</body>
</html>


<?php

// Database configuration (Replace with your actual credentials)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_db_user";
$db_password = "your_db_password";

// Function to register a new user
function registerUser($username, $password, $email) {
  // 1. Data Validation - Important for security
  if (empty($username) || empty($password) || empty($email)) {
    return false; // Indicate failure - missing data
  }

  if (!preg_match('/^[a-zA-Z0-9._-]+$/', $username)) {
    return false; // Invalid username format
  }
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    return false; // Invalid email format
  }

  // 2. Hash the password (VERY IMPORTANT - Security)
  $hashed_password = password_hash($password, PASSWORD_DEFAULT);

  // 3. Database Interaction (Using prepared statements - BEST PRACTICE)
  try {
    $pdo = new PDO("mysql:host=$db_host;dbname=$db_name;charset=utf8mb4", $db_user, $db_password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Enable exception handling

    // Prepare the SQL statement
    $stmt = $pdo->prepare("INSERT INTO users (username, password, email) VALUES (?, ?, ?)");

    // Bind the parameters (use prepared statements for security)
    $stmt->execute([$username, $hashed_password, $email]);

    return true; // Indicate success
  } catch (PDOException $e) {
    // Handle database errors
    error_log("Database error: " . $e->getMessage());  // Log the error for debugging
    return false; // Indicate failure
  } finally {
    // Ensure the connection is closed (best practice)
    $pdo = null;
  }
}


// Example Usage (Demonstration)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST["username"];
  $password = $_POST["password"];
  $email = $_POST["email"];

  if (registerUser($username, $password, $email)) {
    echo "<p style='color: green;'>User registered successfully!</p>";
  } else {
    echo "<p style='color: red;'>Registration failed. Please check your input and try again.</p>";
  }
}
?>
