    Username: <input type="text" name="username" required><br><br>
    Password: <input type="password" name="password" required><br><br>
    Email: <input type="email" name="email" required><br><br>
    <input type="submit" value="Register">
</form>

</body>
</html>


<?php

// Assuming you have a database connection established (e.g., mysqli)
// Replace these with your actual database connection details
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database_name";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Validate input (VERY IMPORTANT!) - Add more robust validation as needed.
  $username = trim($_POST["username"]);
  $email = trim($_POST["email"]);
  $password = trim($_POST["password"]);

  // Basic validation -  Expand this!
  if (empty($username) || empty($email) || empty($password)) {
    $errors = "All fields are required.";
  } elseif (strlen($username) < 3) {
    $errors = "Username must be at least 3 characters long.";
  } elseif (preg_match("/@/", $email)) {
    $errors = "Invalid email format.";
  } elseif (strlen($password) < 8) {
    $errors = "Password must be at least 8 characters long.";
  } else {
    // Sanitize inputs - ALWAYS sanitize before using in a database query
    $username = filter_var($username, FILTER_SANITIZE_STRING);
    $email = filter_var($email, FILTER_SANITIZE_EMAIL);
    $password = filter_var($password, FILTER_SANITIZE_STRING);

    // Hash the password - NEVER store passwords in plain text!
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Prepare the SQL query
    $sql = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$hashed_password')";

    // Execute the query
    $conn = new mysqli($host, $username, $password, $database);

    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }

    if ($conn->query($sql) === TRUE) {
      echo "Registration successful! Please check your email to verify.";
      // Optionally, redirect to a confirmation page.
    } else {
      echo "Error: " . $sql . "<br>" . $conn->error;
    }
  }
}
?>
