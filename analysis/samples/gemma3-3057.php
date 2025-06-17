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

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // Get form data
  $username = $_POST["username"];
  $email = $_POST["email"];
  $password = $_POST["password"];

  // Validate the data (IMPORTANT - This is a basic example.  You should use more robust validation in a production environment)
  if (empty($username) || empty($email) || empty($password)) {
    $errors = "All fields are required.";
  } elseif (strlen($username) < 3) {
    $errors = "Username must be at least 3 characters long.";
  } elseif (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
    $errors = "Invalid email format.";
  } elseif (strlen($password) < 8) {
    $errors = "Password must be at least 8 characters long.";
  }

  // If no errors, process the registration
  if (empty($errors)) {
    // **IMPORTANT:** In a real application, you would:
    // 1. Hash the password (using something like password_hash() )
    // 2. Store the username and hashed password in a database
    // 3.  You would also likely want to check if the username or email already exists.

    // This is a placeholder for demonstration purposes.  It simulates registration.
    $registration_successful = true;

    // Example:  Simulate storing the data
    // You'd replace this with actual database insertion
    $registration_message = "Registration successful!  Welcome, " . htmlspecialchars($username) . "!"; //Escape for security
  } else {
    $registration_message = $errors;
  }
}
?>
