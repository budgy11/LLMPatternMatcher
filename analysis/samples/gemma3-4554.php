    Username: <input type="text" name="username" required><br><br>
    Email: <input type="email" name="email" required><br><br>
    Password: <input type="password" name="password" required><br><br>
    <input type="submit" value="Register">
  </form>

</body>
</html>


<?php

// Database configuration (Replace with your actual credentials)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_db_user";
$db_password = "your_db_password";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get form data
  $username = $_POST["username"];
  $email = $_POST["email"];
  $password = $_POST["password"];

  // Validate input (Important! Prevent security vulnerabilities)
  $errors = [];

  // Username validation
  if (empty($username)) {
    $errors["username"] = "Username cannot be empty.";
  } elseif (strlen($username) < 3) {
    $errors["username"] = "Username must be at least 3 characters.";
  }
  // Email validation (using a simple regex - consider a more robust library)
  if (empty($email)) {
    $errors["email"] = "Email cannot be empty.";
  } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors["email"] = "Invalid email format.";
  }
  // Password validation
  if (empty($password)) {
    $errors["password"] = "Password cannot be empty.";
  } elseif (strlen($password) < 8) {
    $errors["password"] = "Password must be at least 8 characters.";
  }

  // Check for errors
  if (empty($errors)) {
    // Hash the password (VERY IMPORTANT for security!)
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // SQL query to insert the new user
    $sql = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$hashed_password')";

    // Execute the query
    if (mysqli_query($GLOBALS["conn"], $sql)) {
      // Registration successful
      echo "Registration successful! Please check your email for verification instructions.";
    } else {
      // Registration failed
      echo "Registration failed: " . mysqli_error($GLOBALS["conn"]);
    }
  } else {
    // Display errors
    echo "Error: ";
    echo "<ol>";
    foreach ($errors as $error) {
      echo "<li>" . $error . "</li>";
    }
    echo "</ol>";
  }
}

// Connect to the database
$GLOBALS["conn"] = mysqli_connect($db_host, $db_user, $db_password, $db_name);

// Check connection
if ($GLOBALS["conn"]->connect_error) {
  die("Connection failed: " . $GLOBALS["conn"]->connect_error);
}

?>
