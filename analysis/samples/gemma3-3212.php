
    <br><br>

    <input type="submit" value="Register">
</form>

</body>
</html>


<?php

// Database connection details (Replace with your actual credentials)
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database_name";

// Form handling - Check if the registration form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Validate the form data
  $username = trim($_POST["username"]);
  $email = trim($_POST["email"]);
  $password = trim($_POST["password"]);

  // Validation rules (Implement more robust validation as needed)
  $username_regex = "/^[a-zA-Z0-9_]+$/"; // Allow letters, numbers, and underscores
  if (!preg_match($username_regex, $username)) {
    $username_error = "Username must contain only letters, numbers, and underscores.";
  }
  if (empty($username)) {
    $username_error = "Username cannot be empty.";
  }
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $email_error = "Invalid email format.";
  }
  if (empty($email)) {
    $email_error = "Email cannot be empty.";
  }
  if (empty($password)) {
    $password_error = "Password cannot be empty.";
  }
  if (strlen($password) < 8) {
    $password_error = "Password must be at least 8 characters long.";
  }

  // If validation passes, proceed with registration
  if (empty($username_error) && empty($email_error) && empty($password_error)) {

    // Hash the password before storing it in the database
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Prepare the SQL query
    $sql = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$hashed_password')";

    // Execute the query
    $conn = new mysqli($host, $username, $password, $database);

    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }

    if ($conn->query($sql) === TRUE) {
      echo "Registration successful! You have been redirected.";
      // Redirect to a success page or display a success message
      header("Location: success.php"); // Replace success.php with your redirect page
      exit();
    } else {
      echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
  }
}
?>
