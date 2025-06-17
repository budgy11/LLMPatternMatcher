    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required><br><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required><br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required><br><br>

    <label for="confirm_password">Confirm Password:</label>
    <input type="password" id="confirm_password" name="confirm_password" required><br><br>

    <button type="submit">Register</button>
  </form>

</body>
</html>


<?php

// Database credentials - IMPORTANT: Replace with your actual credentials
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_database_user";
$db_password = "your_database_password";

// Form Handling (checking for POST request)
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // Get form data
  $username = $_POST["username"];
  $password = $_POST["password"];
  $email = $_POST["email"];

  // Validation (Essential for security)
  if (empty($username) || empty($password) || empty($email)) {
    $error_message = "All fields are required.";
  } elseif (strlen($username) < 3) {
    $error_message = "Username must be at least 3 characters long.";
  } elseif (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
    $error_message = "Invalid email format.";
  } else {
    // Prepare the SQL query (IMPORTANT: Use prepared statements to prevent SQL injection)
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $sql = "INSERT INTO users (username, password, email) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql); // Assuming $conn is your database connection
    $stmt->bind_param("ss", $username, $hashed_password, $email);
    $stmt->execute();

    if ($stmt->error) {
      $error_message = "Error inserting data: " . $stmt->error;
    } else {
      $success_message = "Registration successful! Please log in.";
    }
  }

}

// Database Connection (Establish connection - replace with your database details)
$conn = new mysqli($db_host, $db_user, $db_password, $db_name);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

?>
