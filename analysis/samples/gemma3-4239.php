        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required><br><br>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br><br>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required><br><br>

        <input type="submit" value="Register">
    </form>

</body>
</html>


<?php

// Database connection details (Replace with your actual credentials)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_database_user";
$db_password = "your_database_password";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get form data
  $username = $_POST["username"];
  $email = $_POST["email"];
  $password = $_POST["password"];
  $confirm_password = $_POST["confirm_password"];

  // Validate the data (IMPORTANT - add more validation as needed)
  $errors = [];

  // Check if username is empty
  if (empty($username)) {
    $errors[] = "Username cannot be empty.";
  }

  // Check if email is empty
  if (empty($email)) {
    $errors[] = "Email cannot be empty.";
  }

  // Basic email validation (you should use a more robust email validation regex)
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "Invalid email format.";
  }

  // Check if passwords match
  if ($password != $confirm_password) {
    $errors[] = "Passwords do not match.";
  }

  // Check if password is empty
  if (empty($password)) {
    $errors[] = "Password cannot be empty.";
  }
  
  // Password strength check (basic - improve for real applications)
  if (strlen($password) < 8) {
    $errors[] = "Password must be at least 8 characters long.";
  }


  // If no errors, proceed with registration
  if (empty($errors)) {
    // Hash the password (IMPORTANT - NEVER store passwords in plain text!)
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // SQL query to insert the user data
    $sql = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$hashed_password')";

    // Execute the query
    $result = mysqli_query($GLOBALS["db_host"], $sql);

    if ($result) {
      // Registration successful
      echo "<p style='color: green;'>User registered successfully!</p>";
    } else {
      // Registration failed
      echo "<p style='color: red;'>Registration failed: " . mysqli_error($GLOBALS["db_host"]) . "</p>";
    }
  } else {
    // Display errors
    echo "<div style='color: red;'>";
    echo "<ul>";
    foreach ($errors as $error) {
      echo "<li>" . $error . "</li>";
    }
    echo "</ul>";
    echo "</div>";
  }
}
?>
