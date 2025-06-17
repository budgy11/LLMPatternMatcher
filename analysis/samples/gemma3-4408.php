        <br><br>

        <button type="submit">Register</button>
    </form>

    <p>Already have an account? <a href="login.php">Login</a></p>

</body>
</html>


<?php

// Database connection details (replace with your actual credentials)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_username";
$db_password = "your_password";

// Function to register a new user
function registerUser($username, $password, $email) {
  // 1. Sanitize and Validate Inputs
  $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
  $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);
  $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);

  // Validate required fields
  if (empty($username) || empty($password) || empty($email)) {
    return false; // Return false if any field is empty
  }

  // Validate email format
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    return false; // Return false if email is invalid
  }

  // 2. Hash the Password (Important for Security)
  $hashed_password = password_hash($password, PASSWORD_DEFAULT);

  // 3. Database Query (using prepared statements - VERY IMPORTANT for security)
  try {
    $pdo = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $pdo->prepare("INSERT INTO users (username, password, email) VALUES (?, ?, ?)");
    $stmt->execute([$username, $hashed_password, $email]);

    return true; // Return true if registration is successful
  } catch (PDOException $e) {
    // Handle database errors (e.g., duplicate username, invalid email)
    error_log("Registration error: " . $e->getMessage()); // Log the error for debugging
    return false;
  }
}


// Example Usage (this part would be in your registration form's submission handler)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST["username"];
  $password = $_POST["password"];
  $email = $_POST["email"];

  if (registerUser($username, $password, $email)) {
    echo "<p>User registered successfully! Please check your email for verification instructions.</p>";
  } else {
    echo "<p>Registration failed. Please try again.</p>";
    // Optionally, provide more specific error messages based on what failed.
    if (strpos($username, 'already exists') !== false) {
      echo "<p>Username already exists. Please choose a different one.</p>";
    } elseif (strpos($username, 'invalid email') !== false) {
      echo "<p>Invalid email address. Please check your input.</p>";
    }
  }
}
?>
