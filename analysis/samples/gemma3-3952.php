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

  <br><br>
  <a href="login.php">Already have an account? Login</a>

</body>
</html>


<?php

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // Validate the form data (important for security!)
  $username = filter_input(INPUT_POST, "username", FILTER_SANITIZE_STRING);
  $email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL);
  $password = filter_input(INPUT_POST, "password", FILTER_SANITIZE_STRING);

  // Check if required fields are filled
  if (empty($username) || empty($email) || empty($password)) {
    $error = "Please fill in all fields.";
  } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $error = "Invalid email format.";
  } elseif (!preg_match("/^(.*@.*)$/", $email)) {
    $error = "Invalid email format.";
  }
  // You should also add password complexity requirements here, 
  //  e.g., minimum length, special characters, etc.


  // If no errors, process the registration
  if (empty($error)) {
    // 1. Hash the password (VERY IMPORTANT - NEVER STORE PLAINTEXT PASSWORDS)
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // 2. Prepare the data for insertion into the database
    $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql); // Assuming $conn is your database connection

    if ($stmt) {
      $stmt->bind_param("ss", $username, $hashed_password);
      $stmt->execute();

      // Check if the insertion was successful
      if ($stmt->affected_rows > 0) {
        // Registration successful - redirect to a success page or login form
        header("Location: login.php"); // Assuming you have a login.php page
        exit();
      } else {
        $error = "Registration failed. Please try again.";
      }
    } else {
      $error = "Database connection error.";
    }
  }
}

?>
