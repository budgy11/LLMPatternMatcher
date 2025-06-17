    Username: <input type="text" name="username" required><br><br>
    Email: <input type="email" name="email" required><br><br>
    Password: <input type="password" name="password" required><br><br>
    Confirm Password: <input type="password" name="confirm_password" required><br><br>
    <input type="submit" value="Register">
  </form>

</body>
</html>


<?php

// Database configuration (replace with your actual database credentials)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_username";
$db_password = "your_password";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // Collect form data
  $username = $_POST["username"];
  $email = $_POST["email"];
  $password = $_POST["password"];
  $confirm_password = $_POST["confirm_password"];

  // Validate the form data
  if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
    $errors = "All fields are required.";
  } elseif (strlen($username) < 3) {
    $errors = "Username must be at least 3 characters long.";
  } elseif (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
    $errors = "Invalid email format.";
  } elseif ($password != $confirm_password) {
    $errors = "Passwords do not match.";
  }

  // If there are no errors, proceed with registration
  if (empty($errors)) {
    // 1. Prepare the SQL query
    $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";

    // 2. Prepare the statement
    $stmt = $conn->prepare($sql); // Use $conn (established connection)

    // 3. Bind the parameters
    $stmt->bind_param("ss", $username, $password); // 's' for string

    // 4. Execute the query
    if ($stmt->execute()) {
      // Registration successful
      $success_message = "Registration successful! Please log in.";
    } else {
      // Registration failed
      $error_message = "Registration failed.  Check your database connection or if the username is already taken.";
    }

    // Close the statement
    $stmt->close();
  }
}

// Establish database connection (do this only once, usually in your main script)
$conn = new mysqli($db_host, $db_user, $db_password, $db_name);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

?>
