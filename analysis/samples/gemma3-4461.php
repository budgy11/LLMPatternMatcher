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

// Database configuration (Replace with your actual database details)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_database_user";
$db_pass = "your_database_password";

// Function to register a new user
function registerUser($username, $password, $email) {
  // 1. Connect to the database
  $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // 2. Hash the password (Important for security!)
  $hashed_password = password_hash($password, PASSWORD_DEFAULT);

  // 3. Prepare the SQL query
  $sql = "INSERT INTO users (username, password, email) VALUES (?, ?, ?)";

  // 4. Prepare the statement
  $stmt = $conn->prepare($sql);

  // 5. Bind the parameters
  $stmt->bind_param("ss", $username, $hashed_password, $email);

  // 6. Execute the query
  if ($stmt->execute()) {
    return true;  // Registration successful
  } else {
    return false; // Registration failed
  }

  // 7. Close the statement and connection
  $stmt->close();
  $conn->close();
}

// Example usage (from a form submission, for instance)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get form data (assuming you have username, password, and email fields)
  $username = $_POST["username"];
  $password = $_POST["password"];
  $email = $_POST["email"];

  // Validate the data (Crucial for security and data integrity)
  if (empty($username) || empty($password) || empty($email)) {
    $errorMessage = "All fields must be filled out.";
  } elseif (strlen($username) < 3) {
    $errorMessage = "Username must be at least 3 characters long.";
  } elseif (preg_match("/@/", $email)) {
    $errorMessage = "Invalid email format.";
  } else {
    // Call the registerUser function
    $registration_result = registerUser($username, $password, $email);

    if ($registration_result) {
      $successMessage = "Registration successful!  Please check your email to verify your account.";
    } else {
      $errorMessage = "Registration failed. Please try again.";
    }
  }
}

?>
