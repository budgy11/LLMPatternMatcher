    <br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" placeholder="Enter password">
    <br><br>

    <button type="submit">Register</button>
  </form>

</body>
</html>


<?php

// Database connection details (replace with your actual credentials)
$db_host = 'localhost';
$db_name = 'your_database_name';
$db_user = 'your_database_user';
$db_password = 'your_database_password';

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Sanitize input data
  $username = trim($_POST["username"]);
  $email = trim($_POST["email"]);
  $password = trim($_POST["password"]);

  // Validate input data (Basic Validation - Enhance this for production)
  if (empty($username) || empty($email) || empty($password)) {
    $error = "All fields are required.";
  } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $error = "Invalid email format.";
  } elseif (strlen($password) < 8) {
    $error = "Password must be at least 8 characters long.";
  }

  // Hash the password (important for security)
  $hashed_password = password_hash($password, PASSWORD_DEFAULT);

  // Insert the user data into the database
  try {
    $pdo = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Enable error handling

    $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
    $stmt->execute([$username, $email, $hashed_password]);

    $success = true; // Registration successful
    $message = "Registration successful! Please check your email to verify your account.";

  } catch (PDOException $e) {
    $error = "An error occurred while registering: " . $e->getMessage();
    $success = false;
  }
}
?>
