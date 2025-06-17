        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required>

        <br><br>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>

        <br><br>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>

        <br><br>

        <label for="confirm_password">Confirm Password:</label>
        <input type="password" id="confirm_password" name="confirm_password" required>

        <br><br>

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
  // Validate and sanitize input data
  $firstname = trim($_POST["firstname"]);
  $lastname = trim($_POST["lastname"]);
  $email = trim($_POST["email"]);
  $password = trim($_POST["password"]);

  //  Input validation (Important for security)
  if (empty($firstname) || empty($lastname) || empty($email) || empty($password)) {
    $error_message = "All fields are required.";
  } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $error_message = "Invalid email format.";
  } elseif (strlen($password) < 8) {
    $error_message = "Password must be at least 8 characters long.";
  } else {
    // Hash the password (Important for security)
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Attempt to connect to the database
    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }

    // Prepare and execute the SQL query
    $sql = "INSERT INTO users (firstname, lastname, email, password) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $firstname, $lastname, $hashed_password);
    $stmt->execute();

    // Display success message
    echo "Registration successful!  Please check your email to verify your account.";
    // Optionally redirect to a login page or another page
    // header("Location: login.php"); // Replace login.php with your login page URL
    // exit();

  }
}
?>
