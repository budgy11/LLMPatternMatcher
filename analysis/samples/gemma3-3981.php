    <label for="username">Username:</label><br>
    <input type="text" id="username" name="username" required><br><br>

    <label for="email">Email:</label><br>
    <input type="email" id="email" name="email" required><br><br>

    <label for="password">Password:</label><br>
    <input type="password" id="password" name="password" required><br><br>

    <button type="submit">Register</button>
  </form>

  <p>Already have an account? <a href="login.php">Log in</a></p>

</body>
</html>


<?php

// Database configuration (Replace with your actual database details)
$db_host = "localhost";
$db_user = "your_db_user";
$db_pass = "your_db_password";
$db_name = "your_db_name";

// Function to register a new user
function registerUser($username, $password, $email) {
  // 1. Validate Input (Important for security)
  $username = trim($username);
  $password = trim($password);
  $email = trim($email);

  // Basic validation (you should add more robust validation here, e.g., email format, password strength)
  if (empty($username) || empty($password) || empty($email)) {
    return "Error: All fields are required.";
  }

  // Check if username already exists (optional but recommended)
  // You should query your database here to check for duplicate usernames.
  // For simplicity, we'll just assume it's unique for this example.

  // 2.  Database Connection (using PDO - preferred)
  try {
    $pdo = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // For better error handling
  } catch (PDOException $e) {
    return "Error connecting to the database: " . $e->getMessage();
  }

  // 3. Prepare the SQL Query
  $sql = "INSERT INTO users (username, password, email) VALUES (:username, :password, :email)";
  $stmt = $pdo->prepare($sql);

  // 4.  Bind Parameters (using placeholders) -  This is crucial for security
  $stmt->bindParam(':username', $username);
  $stmt->bindParam(':password', password_hash($password, PASSWORD_DEFAULT)); // Hash the password!
  $stmt->bindParam(':email', $email);

  // 5.  Execute the Query
  if ($stmt->execute()) {
    return "Registration successful!  You have been logged in.";
  } else {
    return "Error: Could not register user. Database error: " . print_r($stmt->errorInfo(), true);
  }
}


// Example Usage (This is just for demonstration - you'll get this from your form submission)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST["username"];
  $password = $_POST["password"];
  $email = $_POST["email"];

  $registrationResult = registerUser($username, $password, $email);
  echo $registrationResult;
}
?>
