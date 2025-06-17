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

// Database configuration (Replace with your actual database credentials)
$db_host = 'localhost';
$db_name = 'your_database_name';
$db_user = 'your_db_user';
$db_pass = 'your_db_password';

// Error reporting (for debugging - consider disabling in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);


// Function to register a new user
function registerUser($username, $password, $email) {
  global $db_host, $db_name, $db_user, $db_pass;

  // Validate input (Important for security!)
  if (empty($username) || empty($password) || empty($email)) {
    return "Error: All fields are required.";
  }

  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    return "Error: Invalid email address.";
  }

  // Hash the password (VERY IMPORTANT - don't store passwords in plain text)
  $hashed_password = password_hash($password, PASSWORD_DEFAULT);

  // SQL query to insert the new user
  $sql = "INSERT INTO users (username, password, email) VALUES ('$username', '$hashed_password', '$email')";

  // Connect to the database
  $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

  // Check the connection
  if ($conn->connect_error) {
    return "Error: Unable to connect to database - " . $conn->connect_error;
  }

  // Execute the query
  if ($conn->query($sql) === TRUE) {
    return "User registered successfully!";
  } else {
    return "Error: " . $sql . "<br>Error: " . $conn->error;
  }

  // Close the connection
  $conn->close();
}



// --- Example Usage (Form Handling) ---

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get form data
  $username = $_POST["username"];
  $password = $_POST["password"];
  $email = $_POST["email"];

  // Call the registration function
  $registration_result = registerUser($username, $password, $email);

  // Display the result
  echo "<p>" . $registration_result . "</p>";

  // Redirect to a login page or display a success message
  if (strpos($registration_result, 'successfully!') !== false) {
      echo "<p>You have been successfully registered.  <a href='login.php'>Click here to log in.</a></p>";
      // OR:
      // header("Location: success.php"); // Redirect to a success page
      // exit;
  }
}
?>
