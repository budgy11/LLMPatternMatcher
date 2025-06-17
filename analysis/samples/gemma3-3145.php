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

// Form data validation (Basic example - expand for robust validation)
$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $name = $_POST["name"];
  $email = $_POST["email"];
  $password = $_POST["password"];

  // Validate required fields
  if (empty($name) || empty($email) || empty($password)) {
    $errors["message"] = "All fields are required.";
  }

  // Email Validation (more thorough validation is recommended)
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors["message"] = "Invalid email address.";
  }

  // Password Strength (Simple example - improve for security)
  if (strlen($password) < 8) {
    $errors["message"] = "Password must be at least 8 characters long.";
  }

  // Basic email check (to avoid duplicates, ideally with a database check)
  //  This is a simplified example and should be replaced with a proper database query.
  //  This example just checks if the email already exists in a file (not recommended for production)
  $emailExists = file_exists("emails.txt") ? file_get_contents("emails.txt") : false;
  if ($emailExists && $emailExists != $email) {
    $errors["message"] = "This email address is already registered.";
  }

}

//  Database insertion (using prepared statements for security)
if (!empty($errors)) {
  // Handle errors - display them to the user
  echo "<div style='color:red;'>";
  echo "<ul>";
  foreach ($errors as $error) {
    echo "<li>" . $error . "</li>";
  }
  echo "</ul></div>";
} else {
  try {
    // Create database connection
    $conn = new mysqli($host, $username, $password, $database);

    // Check connection
    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }

    // Prepare statement
    $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");

    // Bind parameters (for security - prevents SQL injection)
    $stmt->bind_param("ss", $name, $password);

    // Execute statement
    if ($stmt->execute()) {
      // Success!  Redirect to a success page or display a message.
      echo "<h2>Registration successful!</h2>";
      echo "<p>Please log in using your email and password.</p>";
      //You could also redirect here:
      //header("Location: login.php");
      //exit();
    } else {
      echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $stmt->close();
    $conn->close();
  } catch (Exception $e) {
    echo "An error occurred: " . $e->getMessage();
  }
}

?>
