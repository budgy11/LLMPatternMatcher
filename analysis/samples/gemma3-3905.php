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

// Database configuration (replace with your actual details)
$host     = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database_name";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // Collect form data
  $firstname = $_POST["firstname"];
  $lastname = $_POST["lastname"];
  $email = $_POST["email"];
  $password = $_POST["password"];

  // Validate the email format (basic validation)
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "Invalid email format.";
  }

  // Validate password (basic validation - consider stronger requirements)
  if (strlen($password) < 8) {
    $errors[] = "Password must be at least 8 characters long.";
  }

  // Check if email already exists in the database
  $sql = "SELECT * FROM users WHERE email = '$email'";
  $result = mysqli_query($GLOBALS['host'], $sql); // Use mysqli_query for MySQLi extension
  $user = mysqli_fetch_assoc($result);

  if ($user) {
    $errors[] = "Email already exists. Please register with a different email.";
  }


  // Hash the password (important for security!)
  $hashed_password = password_hash($password, PASSWORD_DEFAULT);


  // If no errors, insert the user into the database
  if (empty($errors)) {
    $sql = "INSERT INTO users (firstname, lastname, email, password) VALUES ('$firstname', '$lastname', '$email', '$hashed_password')";

    if (mysqli_query($GLOBALS['host'], $sql)) {
      // Registration successful
      $success_message = "Registration successful! Please check your email to verify your account.";
    } else {
      // Error during insertion
      $error_message = "Error inserting user.  Please try again.";
    }
  }
}
?>
