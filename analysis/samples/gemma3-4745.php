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

// Database connection details (Replace with your actual credentials)
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "your_database_name";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Collect data from the form
  $firstname = $_POST["firstname"];
  $lastname = $_POST["lastname"];
  $email = $_POST["email"];
  $password = $_POST["password"];

  // Validate the input (Important!  Add more robust validation here)
  if (empty($firstname) || empty($lastname) || empty($email) || empty($password)) {
    $error = "All fields are required.";
  } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $error = "Invalid email address.";
  } elseif (strlen($password) < 8) {
    $error = "Password must be at least 8 characters long.";
  } else {
    // Hash the password (Important for security)
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // SQL query to insert the new user
    $sql = "INSERT INTO users (firstname, lastname, email, password) 
            VALUES ('$firstname', '$lastname', '$email', '$hashed_password')";

    if (mysqli_query($GLOBALS["conn"], $sql)) {
      $success = "Registration successful! You have been redirected to the login page.";
      // Redirect to the login page
      header("Location: login.php");
      exit;  // Stop further execution
    } else {
      $error = "Error: " . mysqli_error($GLOBALS["conn"]);
    }
  }
}
?>
