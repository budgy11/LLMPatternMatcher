    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required>

    <br><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required>

    <br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required>

    <br><br>

    <button type="submit">Register</button>
  </form>

</body>
</html>


<?php

// Database configuration (Replace with your actual credentials)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_database_user";
$db_password = "your_database_password";

// Function to register a user
function registerUser($username, $password, $email) {
  // 1. Data Validation
  if (empty($username) || empty($password) || empty($email)) {
    return false; // Invalid input
  }

  // You can add more robust validation here, like:
  // - Password complexity checks (minimum length, special characters, etc.)
  // - Email format validation (using filter_var)
  // - Check for existing usernames or emails in the database

  // 2.  Hashing the Password (IMPORTANT for Security!)
  $hashed_password = password_hash($password, PASSWORD_DEFAULT);

  // 3. Database Query
  try {
    $pdo = new PDO("mysql:host=$db_host;dbname=$db_name;charset=utf8", $db_user, $db_password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERR_SILENT); // Suppress error messages

    $stmt = $pdo->prepare("INSERT INTO users (username, password, email) VALUES (:username, :password, :email)");
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':password', $hashed_password);
    $stmt->bindParam(':email', $email);
    $stmt->execute();

    return true; // Registration successful
  } catch (PDOException $e) {
    // Handle database errors
    error_log("Database Error: " . $e->getMessage()); // Log the error
    return false; // Registration failed
  }
}


// --- Example Usage (Handle Form Submission)
//  This part would normally be in a separate PHP file (e.g., register.php)

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST["username"];
  $password = $_POST["password"];
  $email = $_POST["email"];

  if (registerUser($username, $password, $email)) {
    echo "Registration successful! Please check your email for verification instructions.";
  } else {
    echo "Registration failed. Please try again.";
  }
}
?>
