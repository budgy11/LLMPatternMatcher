        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required><br><br>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required><br><br>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br><br>

        <input type="submit" value="Register">
    </form>

</body>
</html>


<?php

// Database connection details (Replace with your actual details)
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database_name";

// Form handling - Check if the registration form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // Get form data
  $firstname = $_POST["firstname"];
  $lastname = $_POST["lastname"];
  $email = $_POST["email"];
  $password = $_POST["password"];
  $confirm_password = $_POST["confirm_password"];

  // Validation
  $errors = [];

  // Check if required fields are filled
  if (empty($firstname) || empty($lastname) || empty($email) || empty($password) || empty($confirm_password)) {
    $errors[] = "All fields are required.";
  }

  // Email validation
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "Invalid email format.";
  }

  // Password validation (Minimum length)
  if (strlen($password) < 8) {
    $errors[] = "Password must be at least 8 characters long.";
  }

  // Password confirmation validation
  if ($password != $confirm_password) {
    $errors[] = "Passwords do not match.";
  }

  // Check if there are any errors
  if (count($errors) > 0) {
    // Display errors
    echo "<h2>Error:</h2>";
    foreach ($errors as $error) {
      echo "<p>" . $error . "</p>";
    }
  } else {
    // Sanitize data (Important for security - prevents SQL injection)
    $firstname = mysqli_real_escape_string($connection, $firstname);
    $lastname = mysqli_real_escape_string($connection, $lastname);
    $email = mysqli_real_escape_string($connection, $email);
    $password = password_hash($password, PASSWORD_DEFAULT); // Hash the password securely

    // Database query
    $sql = "INSERT INTO users (firstname, lastname, email, password) VALUES ('$firstname', '$lastname', '$email', '$password')";

    if (mysqli_query($connection, $sql)) {
      // Registration successful
      echo "<h2>Registration successful!</h2>";
      echo "<p>You have successfully created a new account.</p>";
      // Redirect the user (optional)
      header("Location: login.php"); // Redirect to login page
      exit;
    } else {
      echo "Error: " . $sql . "<br>" . $connection->error;
    }
  }

}
?>
