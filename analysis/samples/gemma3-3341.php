        <label for="firstname">First Name:</label>
        <input type="text" id="firstname" name="firstname" required><br><br>

        <label for="lastname">Last Name:</label>
        <input type="text" id="lastname" name="lastname" required><br><br>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required><br><br>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br><br>

        <input type="submit" value="Register">
    </form>

</body>
</html>


<?php

// Include your database connection file (e.g., config.php)
// This file should contain the database connection details.
require_once 'config.php';

// Check if the registration form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // Get form data
  $username = $_POST["username"];
  $email = $_POST["email"];
  $password = $_POST["password"];
  $confirm_password = $_POST["confirm_password"];

  // Validate the input data (VERY IMPORTANT!)
  $errors = [];

  // Username Validation
  if (empty($username)) {
    $errors[] = "Username cannot be empty.";
  }
  if (strlen($username) < 3) {
    $errors[] = "Username must be at least 3 characters long.";
  }

  // Email Validation
  if (empty($email)) {
    $errors[] = "Email cannot be empty.";
  }
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "Invalid email format.";
  }
  // Password Validation
  if (empty($password)) {
    $errors[] = "Password cannot be empty.";
  }
  if (strlen($password) < 8) {
    $errors[] = "Password must be at least 8 characters long.";
  }
  if ($password != $confirm_password) {
    $errors[] = "Passwords do not match.";
  }

  // Check if there are any errors
  if (count($errors) > 0) {
    // Display the errors to the user
    echo "<h2>Error:</h2>";
    echo "<ol>";
    foreach ($errors as $error) {
      echo "<li>" . $error . "</li>";
    }
    echo "</ol>";
  } else {
    // Prepare the SQL query
    $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";

    // Prepare the statement
    $stmt = $conn->prepare($sql);

    // Bind the parameters
    $stmt->bind_param("s", $username, $password);

    // Execute the statement
    if ($stmt->execute()) {
      // Registration successful
      echo "<h2>Registration successful!</h2>";
      echo "<p>You have successfully created a new account.</p>";
      // Redirect to a welcome page or login page
      header("Location: welcome.php"); // Replace welcome.php with your desired page
      exit();
    } else {
      // Registration failed
      echo "<h2>Registration failed!</h2>";
      echo "<p>Error: " . $stmt->error . "</p>";
    }

    // Close the statement
    $stmt->close();
  }
}
?>
