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

// Database connection details (replace with your actual details)
$host     = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // Validate input (basic example - add more robust validation)
  $username = trim($_POST["username"]);
  $password = trim($_POST["password"]);
  $email = trim($_POST["email"]);

  // Check for empty fields
  if (empty($username) || empty($password) || empty($email)) {
    $error = "All fields are required.";
  }
  // Check if username already exists
  if (mysqli_num_rows(mysqli_query($conn, "SELECT username FROM users WHERE username = '$username'")) > 0) {
    $error = "Username already exists.";
  }

  // Password validation (example - you might want to use password_verify with a hashed password)
  if (strlen($password) < 8) {
    $error = "Password must be at least 8 characters long.";
  }

  // Hash the password (Important for security!)
  $hashed_password = password_hash($password, PASSWORD_DEFAULT);

  // If no errors, insert the user into the database
  if (empty($error)) {
    $sql = "INSERT INTO users (username, password, email) VALUES ('$username', '$hashed_password', '$email')";

    if (mysqli_query($conn, $sql)) {
      $success = "Registration successful!  Please check your email to verify your account.";
    } else {
      $error = "Error: " . mysqli_error($conn);
    }
  }
}

// Close the database connection
mysqli_close($conn);

?>
