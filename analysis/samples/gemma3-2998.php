    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required><br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required><br><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required><br><br>

    <label for="confirm_email">Confirm Email:</label>
    <input type="email" id="confirm_email" name="confirm_email" required><br><br>

    <button type="submit">Register</button>
</form>

</body>
</html>


<?php

// Database connection details (replace with your actual values)
$db_host = "localhost";
$db_user = "your_db_user";
$db_password = "your_db_password";
$db_name = "your_db_name";

// Function to register a user
function registerUser($username, $password, $email) {
  // 1. Connect to the database
  $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // 2. Sanitize and validate inputs
  $username = trim($username); // Remove leading/trailing whitespace
  $password = trim($password);
  $email = trim($email);

  // Basic input validation (add more robust validation here)
  if (empty($username) || empty($password) || empty($email)) {
    return false; // Indicate registration failure
  }
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    return false; // Invalid email format
  }

  // Hash the password (IMPORTANT for security)
  $hashed_password = password_hash($password, PASSWORD_DEFAULT);

  // 3. Insert the user into the database
  $sql = "INSERT INTO users (username, password, email) VALUES (?, ?, ?)";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("sss", $username, $hashed_password, $email); // s = string
  if ($stmt->execute()) {
    return true; // Indicate successful registration
  } else {
    return false; // Indicate registration failure
  }

  // 4. Close the database connection
  $stmt->close();
  $conn->close();
}

// Example usage (assuming you have a form with these inputs)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST["username"];
  $password = $_POST["password"];
  $email = $_POST["email"];

  if (registerUser($username, $password, $email)) {
    echo "Registration successful! Please check your email to verify.";
    // Redirect to a confirmation page or login page
  } else {
    echo "Registration failed. Please try again.";
  }
}
?>
