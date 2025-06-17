    <br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" placeholder="Enter password" minlength="6">
    <br><br>

    <input type="submit" value="Register">
  </form>

</body>
</html>


<?php

// Database configuration (replace with your actual credentials)
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database";

// Check if the registration form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Validate the form data
  $name = filter_input(INPUT_POST, "name", FILTER_SANITIZE_STRING);
  $email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL);
  $password = filter_input(INPUT_POST, "password", FILTER_SANITIZE_STRING);

  // Check if required fields are empty
  if (empty($name) || empty($email) || empty($password)) {
    $error = "All fields are required.";
  } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $error = "Invalid email address.";
  } elseif (strlen($password) < 8) {
    $error = "Password must be at least 8 characters long.";
  } else {
    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Prepare the SQL query
    $sql = "INSERT INTO users (name, email, password) VALUES (?, ?, ?)";

    // Prepare the statement
    $stmt = $conn->prepare($sql);

    // Bind the parameters
    $stmt->bind_param("ss", $name, $email, $hashed_password);

    // Execute the query
    if ($stmt->execute()) {
      $success = "Registration successful! You have been redirected to your profile.";
      // Redirect the user to the profile page or login page
      header("Location: profile.php"); // Replace profile.php with your actual profile page
      exit();
    } else {
      $error = "Query failed: " . $stmt->error;
    }
  }
}
?>
