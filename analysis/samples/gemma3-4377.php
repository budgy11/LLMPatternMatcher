

<?php

// Database connection details (Replace with your actual credentials)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_username";
$db_pass = "your_password";

// Function to handle user registration
function registerUser($username, $email, $password) {
  // 1. Validate Inputs (Important for security)
  $username = trim($username); // Remove leading/trailing whitespace
  $email = filter_var($email, FILTER_VALIDATE_EMAIL);
  $password = trim($password);

  if (empty($username) || empty($email) || empty($password)) {
    return "Error: All fields are required.";
  }

  if (strlen($username) < 3 || strlen($username) > 20) {
    return "Error: Username must be between 3 and 20 characters long.";
  }

  if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
    return "Error: Invalid email format.";
  }


  // 2. Hash the Password (Critical for security!)
  $hashed_password = password_hash($password, PASSWORD_DEFAULT);

  // 3. Database Query (Using prepared statements to prevent SQL injection)
  try {
    $pdo = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
    $stmt->execute([$username, $email, $hashed_password]);

    return "User registered successfully!";

  } catch (PDOException $e) {
    return "Error: Database connection failed or query error: " . $e->getMessage();
  }
}


// Example Usage (This is for demonstration purposes, should be handled by a form)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST["username"];
  $email = $_POST["email"];
  $password = $_POST["password"];

  $registration_result = registerUser($username, $email, $password);
  echo "<p>" . $registration_result . "</p>";
}
?>
