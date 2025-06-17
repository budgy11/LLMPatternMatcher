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

// Database configuration (replace with your actual credentials)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_username";
$db_pass = "your_password";

// Function to register a new user
function registerUser($username, $password, $email) {
  // 1. Validate Input (Crucial for security!)
  $username = trim($username);
  $password = trim($password);
  $email = trim($email);

  // Basic input validation
  if (empty($username) || empty($password) || empty($email)) {
    return "Username, password, and email are required.";
  }

  if (strlen($username) < 3 || strlen($username) > 20) {
    return "Username must be between 3 and 20 characters.";
  }

  if (strlen($password) < 8) {
    return "Password must be at least 8 characters long.";
  }

  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    return "Invalid email format.";
  }
  // 2.  Hashing the Password (VERY IMPORTANT - SECURITY!)
  $hashed_password = password_hash($password, PASSWORD_DEFAULT);

  // 3. Database Interaction (using prepared statements for security)
  try {
    // Connect to the database
    $pdo = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);  // Enable exception handling
  } catch (PDOException $e) {
    return "Database connection failed: " . $e->getMessage();
  }


  // Prepare the SQL statement
  $sql = "INSERT INTO users (username, password, email) VALUES (:username, :password, :email)";
  $stmt = $pdo->prepare($sql);

  // Execute the statement
  $stmt->bindParam(':username', $username);
  $stmt->bindParam(':password', $hashed_password);
  $stmt->bindParam(':email', $email);

  if ($stmt->execute()) {
    return "Registration successful! You have been logged in.";
  } else {
    return "Registration failed. Please try again.";
  }

  // 4. Close the connection (important!)
  $pdo = null;
}


// Example Usage (This part would be within a form submission)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST["username"];
  $password = $_POST["password"];
  $email = $_POST["email"];

  $registration_result = registerUser($username, $password, $email);

  // Display the result
  echo "<p>" . $registration_result . "</p>";
}
?>
