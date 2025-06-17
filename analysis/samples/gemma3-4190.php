    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required>

    <br><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required>

    <br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required>

    <br><br>

    <button type="submit">Register</button>
  </form>

  <p>Already have an account? <a href="login.php">Login</a></p>

</body>
</html>


<?php

// Database connection details (replace with your actual values)
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "your_database_name";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // Validate the form input
  $username = trim($_POST["username"]);
  $email = trim($_POST["email"]);
  $password = trim($_POST["password"]);

  // Validation - Basic example, enhance as needed
  if (empty($username) || empty($email) || empty($password)) {
    $errors = "All fields are required.";
  } elseif (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
    $errors = "Invalid email format.";
  } elseif (strlen($password) < 8) {
    $errors = "Password must be at least 8 characters long.";
  } else {
    // Prepare the SQL query
    $sql = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$password')";

    // Execute the query
    $result = mysqli_query($GLOBALS["servername"], $sql);

    if ($result) {
      $success = "Registration successful! You have been redirected.";
      // Redirect the user to a success page or the homepage
      header("Location: success.php"); // Or your desired redirection
      exit();
    } else {
      $errors = "Error registering. Please try again later.";
      error_log("Error registering: " . mysqli_error($GLOBALS["servername"])); // Log the error for debugging
    }
  }
}
?>
