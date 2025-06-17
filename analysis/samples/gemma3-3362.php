    <label for="username">Username:</label>
    <input type="text" id="username" name="username" placeholder="Enter username" required>

    <br><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" placeholder="Enter email" required>

    <br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" placeholder="Enter password" required>

    <br><br>

    <button type="submit">Register</button>
  </form>

</body>
</html>


<?php

// Database connection details (replace with your actual values)
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "your_database_name";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // Validate the form data
  $username = trim($_POST["username"]);
  $email = trim($_POST["email"]);
  $password = trim($_POST["password"]);

  // Basic input validation
  if (empty($username) || empty($email) || empty($password)) {
    $errors = ["username" => "Username cannot be empty.",
              "email" => "Email cannot be empty.",
              "password" => "Password cannot be empty."];
  } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors = ["email" => "Invalid email address."];
  } elseif (strlen($password) < 8) { // Minimum password length
    $errors = ["password" => "Password must be at least 8 characters long."];
  }

  // If no errors, proceed with registration
  if (empty($errors)) {
    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Connect to the database
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }

    // Prepare and execute the SQL query
    $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $username, $email, $hashed_password);
    $stmt->execute();

    // Display success message
    echo "<p style='color: green;'>Registration successful. Please log in.</p>";

    // Close the statement and connection
    $stmt->close();
    $conn->close();

  } else {
    // Display error messages
    echo "<div style='color: red;'>";
    echo "<ul>";
    foreach ($errors as $error => $message) {
      echo "<li>" . $message . "</li>";
    }
    echo "</ul>";
    echo "</div>";
  }
}
?>
