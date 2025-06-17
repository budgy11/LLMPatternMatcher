    <label for="firstname">First Name:</label>
    <input type="text" id="firstname" name="firstname" placeholder="Enter your first name" required><br><br>

    <label for="lastname">Last Name:</label>
    <input type="text" id="lastname" name="lastname" placeholder="Enter your last name" required><br><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" placeholder="Enter your email" required><br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" placeholder="Enter your password" required><br><br>

    <button type="submit">Register</button>
  </form>

</body>
</html>


<?php

// Database connection details - Replace with your actual credentials
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "your_database_name";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Validate the form data
  $username = trim($_POST["username"]);
  $email = trim($_POST["email"]);
  $password = trim($_POST["password"]);

  // Input validation - Add more robust validation as needed
  if (empty($username) || empty($email) || empty($password)) {
    $error[] = "All fields are required.";
  } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $error[] = "Invalid email format.";
  } elseif (strlen($password) < 6) {
    $error[] = "Password must be at least 6 characters long.";
  }
  // Sanitize the inputs (important for security) -  Consider escaping/encoding more thoroughly
  $username = htmlspecialchars($username);
  $email = htmlspecialchars($email);
  $password = htmlspecialchars($password);

  // Hash the password before storing it in the database
  $hashed_password = password_hash($password, PASSWORD_DEFAULT);

  // Connect to the database
  $conn = new mysqli($servername, $username, $password, $dbname);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // Prepare and execute the SQL query
  $sql = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$hashed_password')";

  if ($conn->query($sql) === TRUE) {
    // Registration successful
    echo "Registration successful! Please login.";
  } else {
    // Registration failed
    echo "Error: " . $sql . "<br>" . $conn->error;
  }

  $conn->close();
}
?>
