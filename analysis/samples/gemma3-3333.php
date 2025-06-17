        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>

        <button type="submit">Register</button>
    </form>

</body>
</html>


<?php

// Database connection details (Replace with your actual details)
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "your_database_name";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Validate the form data (Important for security!)
  $username = trim($_POST["username"]);
  $password = trim($_POST["password"]);
  $email = trim($_POST["email"]);

  // Basic validation (Enhance with more robust validation)
  if (empty($username) || empty($password) || empty($email)) {
    $error_message = "All fields are required.";
  } elseif (strlen($username) < 3) {
    $error_message = "Username must be at least 3 characters.";
  } elseif (strlen($password) < 6) {
    $error_message = "Password must be at least 6 characters.";
  } elseif (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
    $error_message = "Invalid email format.";
  } else {
    // Hash the password for security
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Connect to the database
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }

    // Prepare the SQL query
    $sql = "INSERT INTO users (username, password, email) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);

    // Bind the parameters
    $stmt->bind_param("sss", $username, $hashed_password, $email);

    // Execute the query
    if ($stmt->execute()) {
      $success_message = "Registration successful.  You have been logged in.";
      // Optionally redirect to a success page
      // header("Location: success.php");
      // die();
    } else {
      $error_message = "Registration failed.  " . $stmt->error;
    }
  }
}
?>
