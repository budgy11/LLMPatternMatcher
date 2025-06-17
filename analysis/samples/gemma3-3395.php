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

// Database configuration (replace with your actual details)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_database_user";
$db_pass = "your_database_password";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Validate the form data
  $username = trim($_POST["username"]);
  $email = trim($_POST["email"]);
  $password = trim($_POST["password"]);

  // Validation - Add more robust validation as needed
  if (empty($username) || empty($email) || empty($password)) {
    $errors = "All fields are required.";
  } elseif (strlen($username) < 3) {
    $errors = "Username must be at least 3 characters.";
  } elseif (preg_match("/@/", $email)) {
    $errors = "Invalid email format.";
  } elseif (strlen($password) < 8) {
    $errors = "Password must be at least 8 characters.";
  } else {
    // Hash the password before storing it in the database
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Prepare the SQL query
    $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";

    // Prepare the statement
    $stmt = $conn->prepare($sql); // Assuming you have a $conn object

    // Bind the parameters
    $stmt->bind_param("sss", $username, $email, $hashed_password); // "s" for string, "i" for integer, "d" for double

    // Execute the query
    if ($stmt->execute()) {
      $success = "Registration successful!  Please check your email to confirm your account.";
    } else {
      $errors = "Registration failed. " . $stmt->error;
    }

    // Close the statement
    $stmt->close();
  }
}

// Display errors or success message
if (isset($errors)) {
  echo "<div class='error'>$errors</div>";
}

if (isset($success)) {
  echo "<div class='success'>$success</div>";
}

// Display the registration form
?>
