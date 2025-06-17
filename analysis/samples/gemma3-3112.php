    <br><br>

    <button type="submit">Register</button>
  </form>

</body>
</html>


<?php

// Database connection details (Replace with your actual credentials)
$db_host = 'localhost';
$db_name = 'your_database_name';
$db_user = 'your_db_user';
$db_pass = 'your_db_password';

// Function to handle user registration
function registerUser($username, $email, $password) {
  // 1. Data Validation (Important!)
  if (empty($username) || empty($email) || empty($password)) {
    return "Error: All fields are required.";
  }

  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    return "Error: Invalid email format.";
  }

  // Hash the password for security.  Use a strong hashing algorithm.
  $hashed_password = password_hash($password, PASSWORD_DEFAULT); 

  // 2. Database Insertion
  try {
    $pdo = new PDO("mysql:host=$db_host;dbname=$db_name;charset=utf8", $db_user, $db_pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERR_EXCEPTION);  //Error handling
  
    $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
    $stmt->execute([$username, $email, $hashed_password]);
    return "Registration successful!  You have been logged in.";

  } catch (PDOException $e) {
    return "Error: " . $e->getMessage();
  }
}


// --- Example Usage (for testing - not part of the function itself) ---
// This part is for running the registration process
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST["username"];
  $email = $_POST["email"];
  $password = $_POST["password"];

  $registration_result = registerUser($username, $email, $password);
  echo $registration_result;
}
?>
