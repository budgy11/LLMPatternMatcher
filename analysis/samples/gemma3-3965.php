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

// Database Configuration (Replace with your actual credentials)
$db_host = 'localhost';
$db_name = 'your_database_name';
$db_user = 'your_database_user';
$db_password = 'your_database_password';

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get form data
  $username = $_POST["username"];
  $email = $_POST["email"];
  $password = $_POST["password"];

  // Validate the input (Important for security!)
  if (empty($username) || empty($email) || empty($password)) {
    $errors = array("username" => "Username cannot be empty.",
                   "email" => "Email cannot be empty.",
                   "password" => "Password cannot be empty.");
    // Display error messages
    displayErrors($errors);
    // Exit to prevent further processing
    exit;
  }

  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors = array("email" => "Invalid email format.");
    displayErrors($errors);
    exit;
  }

  // Password validation (basic - adjust to your security requirements)
  if (strlen($password) < 8) {
    $errors = array("password" => "Password must be at least 8 characters long.");
    displayErrors($errors);
    exit;
  }

  // Hash the password before storing it in the database
  $hashed_password = password_hash($password, PASSWORD_DEFAULT);

  // Connect to the database
  $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // Prepare and execute the SQL query
  $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("ss", $username, $hashed_password);  // "ss" means two strings

  if ($stmt->execute()) {
    // Registration successful
    echo "Registration successful!  You have been redirected.";
    // Optionally redirect to a login page or homepage
    // header("Location: login.php");
    exit;
  } else {
    // Registration failed
    echo "Registration failed: " . $conn->error;
    exit;
  }

  // Close the statement and connection
  $stmt->close();
  $conn->close();
}

// Function to display error messages
function displayErrors($errors) {
  echo "<div class='error-messages'>";
  foreach ($errors as $key => $value) {
    echo "<span class='error-text'>" . $value . "</span><br>";
  }
  echo "</div>";
}

?>
