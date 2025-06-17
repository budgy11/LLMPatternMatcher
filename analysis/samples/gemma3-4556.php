    <label for="username">Username:</label>
    <input type="text" id="username" name="username" placeholder="Enter username"><br><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" placeholder="Enter email"><br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" placeholder="Enter password"><br><br>

    <input type="submit" value="Register">
  </form>

</body>
</html>


<?php

// Database connection details (replace with your actual details)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_database_user";
$db_pass = "your_database_password";

// Form data validation (basic example)
function validateRegistrationData($name, $email, $password) {
  // Check if all fields are filled
  if (empty($name) || empty($email) || empty($password)) {
    return false;
  }

  // Basic email validation (can be improved with regex)
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    return false;
  }

  // Password requirements (e.g., minimum length)
  if (strlen($password) < 8) {
    return false;
  }

  return true;
}

// Function to register a new user
function registerUser($name, $email, $password) {
  // 1. Validate the data
  if (!validateRegistrationData($name, $email, $password)) {
    return "Invalid registration data. Please check your information.";
  }

  // 2. Hash the password (VERY IMPORTANT for security)
  $hashed_password = password_hash($password, PASSWORD_DEFAULT);

  // 3. Connect to the database
  $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // 4. Prepare and execute the SQL query
  $sql = "INSERT INTO users (name, email, password) VALUES (?, ?, ?)";
  $stmt = $conn->prepare($sql);

  if ($stmt) {
    $stmt->bind_param("ss", $name, $email, $hashed_password);
    $stmt->execute();
    $stmt->close();
  } else {
    echo "Error preparing statement.";
    return false;
  }

  // 5. Close the database connection
  $conn->close();

  return true; // Indicate successful registration
}



// Handle form submission (example - replace with your actual form processing)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $name = $_POST["name"];
  $email = $_POST["email"];
  $password = $_POST["password"];

  // Process registration
  $registrationResult = registerUser($name, $email, $password);

  if ($registrationResult == true) {
    echo "<p>Registration successful! You have been redirected.</p>";
    // Redirect to a success page or similar.
  } else {
    echo "<p>Registration failed: " . $registrationResult . "</p>";
  }
}
?>
