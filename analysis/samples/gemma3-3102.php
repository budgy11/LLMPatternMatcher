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

// Database connection details (replace with your actual credentials)
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database";

// Form handling (checking if it's a POST request)
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // Get form data
  $firstname = $_POST["firstname"];
  $lastname = $_POST["lastname"];
  $email = $_POST["email"];
  $password = $_POST["password"];

  // Validate the email (basic validation - improve as needed)
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $email_error = "Invalid email address.";
  }

  // Validate password (basic validation - improve as needed)
  if (strlen($password) < 8) {
    $password_error = "Password must be at least 8 characters long.";
  }

  // Sanitize and validate input (essential to prevent security vulnerabilities)
  $firstname = filter_input(INPUT_POST, 'firstname', FILTER_SANITIZE_STRING);
  $lastname = filter_input(INPUT_POST, 'lastname', FILTER_SANITIZE_STRING);
  $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL); // Specifically for email
  $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);

  // Hash the password (very important for security!)
  $hashed_password = password_hash($password, PASSWORD_DEFAULT);

  // Connect to the database
  $conn = new mysqli($host, $username, $password, $database);

  // Check the connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // Prepare the SQL query (using prepared statements - crucial for security!)
  $sql = "INSERT INTO users (firstname, lastname, email, password) VALUES (?, ?, ?, ?)";
  $stmt = $conn->prepare($sql);

  if ($stmt) {
    // Bind parameters
    $stmt->bind_param("sss", $firstname, $email, $hashed_password);

    // Execute the query
    if ($stmt->execute()) {
      $success_message = "Registration successful! Please check your email to activate your account.";
    } else {
      $error_message = "Registration failed: " . $stmt->error;
    }
  } else {
    $error_message = "Error preparing statement.";
  }
  // Close the statement and connection
  $stmt->close();
  $conn->close();
}

?>
