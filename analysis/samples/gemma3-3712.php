    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required><br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required><br><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required><br><br>

    <button type="submit">Register</button>
</form>


<?php

// Database connection details (replace with your actual credentials)
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "your_database_name";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // Get form data
  $firstname = $_POST["firstname"];
  $lastname = $_POST["lastname"];
  $email = $_POST["email"];
  $password = $_POST["password"];

  // Validation (IMPORTANT - Add more robust validation as needed)
  if (empty($firstname) || empty($lastname) || empty($email) || empty($password)) {
    $error_message = "All fields are required.";
  } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $error_message = "Invalid email format.";
  } elseif (strlen($password) < 6) {
    $error_message = "Password must be at least 6 characters long.";
  }

  // Hash the password (SECURITY - VERY IMPORTANT)
  $hashed_password = password_hash($password, PASSWORD_DEFAULT);

  // Connect to the database
  $conn = new mysqli($servername, $username, $password, $dbname);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // Prepare and execute the SQL query
  $sql = "INSERT INTO users (firstname, lastname, email, password) VALUES ('$firstname', '$lastname', '$email', '$hashed_password')";

  if ($conn->query($sql) === TRUE) {
    // Registration successful
    $success_message = "Registration successful! Please check your email to verify your account.";
  } else {
    // Registration failed
    $error_message = "Error: " . $conn->error;
  }

  // Close the connection
  $conn->close();
}
?>
