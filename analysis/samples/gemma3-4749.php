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

// Database configuration (Replace with your actual database details)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_database_user";
$db_password = "your_database_password";

// Function to register a new user
function registerUser($username, $password, $email) {
  // 1. Validate Inputs (Crucial for security)
  $username = trim($username);
  $password = trim($password);
  $email = trim($email);

  // Check for empty fields
  if (empty($username) || empty($password) || empty($email)) {
    return "Error: All fields are required.";
  }

  // Basic username validation (you can add more complex validation here)
  if (strlen($username) < 3 || strlen($username) > 20) {
    return "Error: Username must be between 3 and 20 characters.";
  }

  // Email Validation (using a basic regex)
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    return "Error: Invalid email format.";
  }


  // 2. Connect to the Database
  $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

  // Check connection
  if ($conn->connect_error) {
    return "Error: Connection failed: " . $conn->connect_error;
  }

  // 3. Prepare and Execute the SQL Query
  $hashed_password = password_hash($password, PASSWORD_DEFAULT);  // Hash the password

  $sql = "INSERT INTO users (username, password, email) VALUES (?, ?, ?)";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("ssi", $username, $hashed_password, $email);

  if ($stmt->execute()) {
    $stmt->close();
    return "Registration successful! You have been logged in.";
  } else {
    $stmt->close();
    return "Error: " . $conn->error;
  }
}

// Example Usage (for testing - you'll typically handle this through a form submission)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST["username"];
  $password = $_POST["password"];
  $email = $_POST["email"];

  $registration_result = registerUser($username, $password, $email);
  echo $registration_result;
} else {
  // Display the registration form (if not submitting a form)
  ?>
