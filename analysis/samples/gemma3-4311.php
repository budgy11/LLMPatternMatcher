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

// Database connection details (replace with your actual credentials)
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "your_database_name";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Validate the form data (important for security!)
  $username = trim($_POST["username"]);
  $email = trim($_POST["email"]);
  $password = trim($_POST["password"]);

  // Sanitize the inputs to prevent SQL injection and cross-site scripting (XSS)
  $username = mysqli_real_escape_string($conn, $username);
  $email = mysqli_real_escape_string($conn, $email);
  $password = mysqli_real_escape_string($conn, $password);

  // Check if username and email are empty
  if (empty($username) || empty($email) || empty($password)) {
    $error[] = "All fields are required.";
  } else {
    // Validate email format (basic validation - you can use a more robust regex)
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $error[] = "Invalid email format.";
    }

    // Check if the username already exists in the database
    $sql = "SELECT * FROM users WHERE username = '$username'";
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0) {
      $error[] = "Username already exists. Please choose another.";
    }

    // Hash the password before storing it in the database
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // If no errors, insert the user into the database
    if (empty($error)) {
      $sql = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$hashed_password')";
      if (mysqli_query($conn, $sql)) {
        // Registration successful!  Redirect to a welcome page or login page
        header("Location: welcome.php"); // Replace with your welcome page
        exit();
      } else {
        $error[] = "Error inserting data into database.";
      }
    }
  }
}
?>
