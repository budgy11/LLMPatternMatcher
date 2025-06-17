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

// Database credentials (replace with your actual credentials)
$db_host = "localhost";
$db_user = "your_username";
$db_password = "your_password";
$db_name = "your_database_name";

// Function to register a new user
function registerUser($username, $email, $password) {
  // 1. Connect to the database
  $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // 2. Sanitize and Validate Input (VERY IMPORTANT!)
  // Escape the input to prevent SQL injection
  $username = $conn->real_escape_string($username);
  $email = $conn->real_escape_string($email);
  
  // Check if username or email is empty
  if (empty($username) || empty($email) || empty($password)) {
    return "Please fill in all fields.";
  }

  // Basic email validation (can be improved)
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    return "Invalid email address.";
  }


  // 3. Hash the password (For security!)
  $hashed_password = password_hash($password, PASSWORD_DEFAULT);

  // 4. Prepare and execute the SQL query
  $sql = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$hashed_password')";

  if ($conn->query($sql) === TRUE) {
    return "Registration successful! You have been redirected.";
  } else {
    return "Error: " . $conn->error;
  }
}


// Example Usage (for demonstration purposes - in a real application, this would come from a form)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST["username"];
  $email = $_POST["email"];
  $password = $_POST["password"];

  $registration_message = registerUser($username, $email, $password);
  echo "<p>" . $registration_message . "</p>";
}

?>
