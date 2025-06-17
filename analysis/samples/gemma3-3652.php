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

// Database connection details (replace with your actual values)
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

  // Validate username
  if (empty($username)) {
    $username_error = "Username cannot be empty.";
  } elseif (strlen($username) < 3) {
    $username_error = "Username must be at least 3 characters long.";
  }

  // Validate email
  if (empty($email)) {
    $email_error = "Email cannot be empty.";
  } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $email_error = "Invalid email format.";
  }

  // Validate password
  if (empty($password)) {
    $password_error = "Password cannot be empty.";
  } elseif (strlen($password) < 6) {
    $password_error = "Password must be at least 6 characters long.";
  }


  // Hash the password (VERY IMPORTANT - Never store plain text passwords!)
  $hashed_password = password_hash($password, PASSWORD_DEFAULT);

  // Check if there are any validation errors
  if ($username_error || $email_error || $password_error) {
    $errors = array(
      "username_error" => $username_error,
      "email_error" => $email_error,
      "password_error" => $password_error
    );
  } else {
    // Insert the user data into the database
    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }

    // Prepare the SQL query
    $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";

    // Use prepared statements to prevent SQL injection
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $username, $email, $hashed_password); // "ss" means two strings

    if ($stmt->execute()) {
      // Registration successful
      echo "Registration successful!  Please check your email for verification."
      // You might want to redirect the user to a login page here
    } else {
      // Registration failed
      echo "Registration failed: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
  }
}
?>
