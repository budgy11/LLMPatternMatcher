    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required><br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required><br><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required><br><br>

    <input type="submit" value="Register">
  </form>

</body>
</html>


<?php

// Database connection details (replace with your actual credentials)
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "your_database_name";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Validate the form data (basic validation - enhance for production)
  $username = trim($_POST["username"]);
  $email = trim($_POST["email"]);
  $password = trim($_POST["password"]);

  if (empty($username) || empty($email) || empty($password)) {
    $error = "All fields are required.";
  } elseif (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
    $error = "Invalid email format.";
  } elseif (strlen($password) < 6) {
    $error = "Password must be at least 6 characters long.";
  } else {
    // Hash the password - VERY IMPORTANT for security!
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Attempt to connect to the database
    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }

    // Prepare the SQL query
    $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
      $stmt->bind_param("ss", $username, $email, $hashed_password);
      $stmt->execute();
      $stmt->close();

      // Success message
      $success = "Registration successful! Please log in.";

      // Redirect to the login page (or homepage)
      header("Location: login.php"); // Assuming you have a login.php page
      exit();
    } else {
      $error = "Error preparing statement.";
    }
  }
}
?>
