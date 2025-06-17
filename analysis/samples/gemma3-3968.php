

<?php

// Database connection details (replace with your actual credentials)
$db_host = "localhost";
$db_user = "your_db_user";
$db_password = "your_db_password";
$db_name = "your_db_name";

// Function to register a new user
function registerUser($username, $password, $email) {
  // 1. Validate Input (Crucial for security - don't skip this!)
  $username = trim($username);
  $password = trim($password);
  $email = trim($email);

  // Check if username and email are empty
  if (empty($username) || empty($email)) {
    return "Username and email are required.";
  }

  // Basic username validation (can be extended)
  if (strlen($username) < 3 || strlen($username) > 20) {
    return "Username must be between 3 and 20 characters long.";
  }

  // Basic email validation (can be improved with regex)
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    return "Invalid email format.";
  }

  // 2. Hash the Password (Very Important - Don't store passwords in plain text!)
  $hashed_password = password_hash($password, PASSWORD_DEFAULT);

  // 3. Connect to the Database
  try {
    $conn = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  } catch (PDOException $e) {
    return "Database connection failed: " . $e->getMessage();
  }

  // 4. Prepare and Execute the SQL Query
  try {
    $stmt = $conn->prepare("INSERT INTO users (username, password, email) VALUES (?, ?, ?)");
    $stmt->execute([$username, $hashed_password, $email]);
    return "Registration successful!  You have been logged in."; // Or redirect to a success page
  } catch (PDOException $e) {
    return "Registration failed: " . $e->getMessage();
  } finally {
    $conn = null; // Close the connection
  }
}

// Example usage (This part is for testing.  It should be in a separate handler, not the registration form itself.)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST["username"];
  $password = $_POST["password"];
  $email = $_POST["email"];

  $registration_result = registerUser($username, $password, $email);
  echo $registration_result;
}
?>
