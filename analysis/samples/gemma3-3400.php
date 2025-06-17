    <br><br>

    <button type="submit">Register</button>
  </form>

</body>
</html>


<?php

// Database configuration (replace with your actual credentials)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_username";
$db_password = "your_password";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Sanitize the input data
  $username = trim($_POST["username"]);
  $email = trim($_POST["email"]);
  $password = trim($_POST["password"]);

  // Validate the input
  $errors = [];

  // Check if username is empty
  if (empty($username)) {
    $errors[] = "Username cannot be empty.";
  }
  // Validate username format (e.g., alphanumeric and at least 3 characters)
  if (!preg_match('/^[a-zA-Z0-9]{3,}$/', $username)) {
    $errors[] = "Username must be at least 3 characters and contain only letters and numbers.";
  }

  // Check if email is empty
  if (empty($email)) {
    $errors[] = "Email cannot be empty.";
  }
  // Validate email format
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "Invalid email format.";
  }

  // Check if password is empty
  if (empty($password)) {
    $errors[] = "Password cannot be empty.";
  }
  // Validate password strength (e.g., at least 8 characters)
  if (strlen($password) < 8) {
    $errors[] = "Password must be at least 8 characters long.";
  }


  // If no errors, process the registration
  if (empty($errors)) {
    // Hash the password before storing it in the database
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Connect to the database
    $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

    // Check connection
    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }

    // Prepare the SQL query
    $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";

    // Prepare the statement
    $stmt = $conn->prepare($sql);

    // Bind the parameters
    $stmt->bind_param("ss", $username, $hashed_password);

    // Execute the query
    if ($stmt->execute()) {
      // Registration successful
      echo "Registration successful! Please check your email for verification.";
    } else {
      // Registration failed
      echo "Registration failed: " . $stmt->error;
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
    
  }
}
?>
