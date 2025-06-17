    <label for="firstname">First Name:</label>
    <input type="text" id="firstname" name="firstname" placeholder="John" required><br><br>

    <label for="lastname">Last Name:</label>
    <input type="text" id="lastname" name="lastname" placeholder="Doe" required><br><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" placeholder="yourname@example.com" required><br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" minlength="8" required><br><br>

    <label for="confirm_password">Confirm Password:</label>
    <input type="password" id="confirm_password" name="confirm_password" minlength="8" required><br><br>

    <button type="submit">Register</button>
  </form>

</body>
</html>


<?php

// Database configuration (replace with your actual values)
$db_host = 'localhost';
$db_name = 'your_database_name';
$db_user = 'your_db_user';
$db_pass = 'your_db_password';

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // Validate the form data
  $username = trim($_POST["username"]);
  $email = trim($_POST["email"]);
  $password = trim($_POST["password"]);

  // Basic validation (you should add more robust validation here)
  if (empty($username) || empty($email) || empty($password)) {
    $errors = "All fields are required.";
  } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors = "Invalid email format.";
  } elseif (strlen($password) < 6) {
    $errors = "Password must be at least 6 characters long.";
  }
    
  // If no errors, proceed with registration
  if (empty($errors)) {
    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Prepare the SQL query
    $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";

    // Prepare the statement
    $stmt = $conn->prepare($sql);

    // Bind the parameters
    $stmt->bind_param("sss", $username, $email, $hashed_password);

    // Execute the query
    if ($stmt->execute()) {
      // Registration successful
      echo "Registration successful! Please check your email for verification.";
      // You might want to redirect the user or display a success message
    } else {
      // Registration failed
      echo "Registration failed. " . $conn->error;
    }

    // Close the statement
    $stmt->close();
  }
}
?>
