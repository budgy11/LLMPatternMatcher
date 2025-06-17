        <label for="username">Username:</label>
        <input type="text" id="username" name="username" placeholder="Enter username">
        <br><br>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" placeholder="Enter email">
        <br><br>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" placeholder="Enter password">
        <br><br>

        <button type="submit">Register</button>
    </form>

</body>
</html>


<?php

// Database connection details (Replace with your actual credentials)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_username";
$db_password = "your_password";

// Check if the registration form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Sanitize input data to prevent security vulnerabilities (SQL injection, XSS)
  $username = trim($_POST["username"]);
  $email = trim($_POST["email"]);
  $password = trim($_POST["password"]);

  // Validate the input data
  $errors = [];

  if (empty($username)) {
    $errors[] = "Username cannot be empty.";
  }
  if (empty($email)) {
    $errors[] = "Email cannot be empty.";
  }
  if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
    $errors[] = "Invalid email format.";
  }
  if (empty($password)) {
    $errors[] = "Password cannot be empty.";
  }
  if (strlen($password) < 8) {
    $errors[] = "Password must be at least 8 characters long.";
  }


  // Check if there are any errors
  if (empty($errors)) {
    // Hash the password using password_hash() -  Important for security
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Prepare the SQL query
    $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";

    // Prepare the statement
    $stmt = $conn->prepare($sql);

    // Bind the parameters
    $stmt->bind_param("ss", $username, $hashed_password);

    // Execute the query
    if ($stmt->execute()) {
      // Registration successful
      echo "Registration successful! You have been redirected.";
      // Redirect to a success page or home page
      header("Location: success.php");
      exit;
    } else {
      // Error executing the query
      echo "Error: " . $stmt->error;
    }
  } else {
    // Display the errors to the user
    echo "<ul>";
    foreach ($errors as $error) {
      echo "<li>" . $error . "</li>";
    }
    echo "</ul>";
  }
}

//  Database connection (Establish connection)
$conn = new mysqli($db_host, $db_user, $db_password, $db_name);

// Check the connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

?>
