    Username: <input type="text" name="username" required><br><br>
    Password: <input type="password" name="password" required><br><br>
    Email: <input type="email" name="email" required><br><br>
    <input type="submit" value="Register">
  </form>

</body>
</html>


<?php

// Database configuration (replace with your actual values)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_database_user";
$db_pass = "your_database_password";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // Get form data
  $username = $_POST["username"];
  $email = $_POST["email"];
  $password = $_POST["password"];
  $confirm_password = $_POST["confirm_password"];

  // Validation (basic - enhance this for production!)
  if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
    $error_message = "All fields are required.";
  } elseif (strlen($username) < 3) {
    $error_message = "Username must be at least 3 characters long.";
  } elseif (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
    $error_message = "Invalid email format.";
  } elseif ($password != $confirm_password) {
    $error_message = "Passwords must match.";
  }

  // If no errors, proceed with registration
  if (empty($error_message)) {
    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Prepare the SQL query
    $sql = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$hashed_password')";

    // Execute the query
    $result = mysqli_query($GLOBALS["db_host"], $sql);

    if ($result) {
      // Registration successful
      $success_message = "Registration successful!  Please check your email to activate your account.";
    } else {
      // Registration failed
      $error_message = "Error registering. Please try again later.";
    }
  }
}

?>
