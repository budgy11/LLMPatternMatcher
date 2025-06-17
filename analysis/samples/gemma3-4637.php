    <br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" value="">
    <br><br>

    <button type="submit">Register</button>
</form>

</body>
</html>


<?php

// Database configuration (Replace with your actual database details)
$host = "localhost";
$username = "your_db_username";
$password = "your_db_password";
$database = "your_db_name";

// Error reporting (for debugging)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Function to register a new user
function registerUser($username, $password, $email) {
  // Connect to the database
  $conn = new mysqli($host, $username, $password, $database);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // Hash the password (important for security!)
  $hashed_password = password_hash($password, PASSWORD_DEFAULT);

  // SQL query to insert the user data
  $sql = "INSERT INTO users (username, password, email) VALUES (?, ?, ?)";

  // Prepare statement (recommended for security)
  $stmt = $conn->prepare($sql);

  if ($stmt) {
    // Bind parameters
    $stmt->bind_param("ss", $username, $hashed_password, $email);

    // Execute the statement
    if ($stmt->execute()) {
      // Success!
      $stmt->close();
      return true;
    } else {
      // Error executing query
      $error = $stmt->error;
      $stmt->close();
      return false;
    }
  } else {
    // Error preparing statement
    $stmt->close();
    return false;
  }
}

// Example Usage (Handle form submission)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get form data
  $username = $_POST["username"];
  $password = $_POST["password"];
  $email = $_POST["email"];

  // Validate input (Very important!)  Add more validation as needed
  if (empty($username) || empty($password) || empty($email)) {
    $error = "All fields are required.";
  } elseif (strlen($username) < 3) {
    $error = "Username must be at least 3 characters.";
  } elseif (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
    $error = "Invalid email format.";
  } else {
    // Register the user
    if (registerUser($username, $password, $email)) {
      $success = "Registration successful! You have been redirected.";
    } else {
      $error = "Registration failed. Please try again.";
    }
  }
}

?>
