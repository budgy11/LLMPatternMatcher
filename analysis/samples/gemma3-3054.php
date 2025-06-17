
    <br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" placeholder="Enter password" required>

    <br><br>

    <input type="submit" value="Register">
  </form>

</body>
</html>


<?php

// Database credentials (Replace with your actual values)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_username";
$db_password = "your_password";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // Get form data
  $username = $_POST["username"];
  $email = $_POST["email"];
  $password = $_POST["password"];

  // Validate input (Basic validation - you should expand this!)
  if (empty($username) || empty($email) || empty($password)) {
    $errors = "All fields are required.";
  } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors = "Invalid email format.";
  } elseif (strlen($password) < 8) {
    $errors = "Password must be at least 8 characters long.";
  }
    
  // Hash the password
  $hashed_password = password_hash($password, PASSWORD_DEFAULT);

  // Database connection
  $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // Prepare the SQL query
  $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";

  // Prepare statement
  $stmt = $conn->prepare($sql);

  // Bind parameters
  $stmt->bind_param("ss", $username, $hashed_password, $email);  // "ss" indicates two string parameters

  // Execute the query
  if ($stmt->execute()) {
    // Registration successful
    echo "Registration successful!  Please verify your email.";
    // Optionally, redirect to a login page or confirmation page
    // header("Location: login.php");
  } else {
    // Registration failed
    echo "Registration failed: " . $stmt->error;
  }

  // Close the statement and connection
  $stmt->close();
  $conn->close();
}
?>
