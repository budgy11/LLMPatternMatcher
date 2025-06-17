    Username: <input type="text" name="username" required><br><br>
    Email: <input type="email" name="email" required><br><br>
    Password: <input type="password" name="password" required><br><br>
    Confirm Password: <input type="password" name="confirm_password" required><br><br>

    <input type="submit" value="Register">
</form>

</body>
</html>


<?php

// Database connection details (Replace with your actual credentials)
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "your_database";

// Form handling (for simplicity, we'll use a simple HTML form)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST["username"];
  $email = $_POST["email"];
  $password = $_POST["password"];

  // Validation (important to prevent security issues)
  if (empty($username) || empty($email) || empty($password)) {
    $error = "All fields are required.";
  } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $error = "Invalid email format.";
  } elseif (strlen($password) < 8) {
    $error = "Password must be at least 8 characters long.";
  } else {
    //  Database insertion
    try {
      $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
      $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

      // Prepare the SQL statement
      $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (:username, :email, :password)");

      // Bind the parameters
      $stmt->bindParam(':username', $username);
      $stmt->bindParam(':email', $email);
      $stmt->bindParam(':password', $password);

      // Execute the statement
      $stmt->execute();

      // Success message
      $success = "Registration successful! Please log in.";
    } catch (PDOException $e) {
      $error = "Registration failed: " . $e->getMessage();
    }
  }
}
?>
