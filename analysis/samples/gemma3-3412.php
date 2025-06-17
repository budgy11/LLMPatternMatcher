    <br><br>

    <button type="submit">Register</button>
  </form>

  <p>Already have an account? <a href="login.php">Login</a></p> <!-- Replace "login.php" with your login page -->

</body>
</html>


<?php

// Database Configuration (Replace with your actual database details)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_database_user";
$db_password = "your_database_password";

// Function to register a new user
function registerUser($username, $password, $email) {
  // 1. Connect to the database
  $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // 2. Sanitize and Validate Inputs (Crucial for Security!)
  $username = trim($username); // Remove whitespace
  $password = trim($password);
  $email = trim($email);

  // Check if username or email is empty
  if (empty($username) || empty($email)) {
    return false; // Indicate failure
  }

  // Basic email validation (you can add more robust validation)
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    return false;
  }

  // 3. Hash the password (VERY IMPORTANT for security)
  $hashed_password = password_hash($password, PASSWORD_DEFAULT);

  // 4. Prepare and Execute the SQL Query
  $sql = "INSERT INTO users (username, password, email) VALUES (?, ?, ?)";
  $stmt = $conn->prepare($sql);

  if ($stmt) {
    $stmt->bind_param("ss", $username, $hashed_password, $email);
    $stmt->execute();
    $stmt->close();
  } else {
    // Handle error preparing the statement
    echo "Error preparing statement: " . $conn->error;
    return false;
  }

  // 5. Close the connection
  $conn->close();

  return true; // Indicate success
}

// Example Usage (Demonstration)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST["username"];
  $password = $_POST["password"];
  $email = $_POST["email"];

  if (registerUser($username, $password, $email)) {
    echo "Registration successful! Please check your email to verify your account.";
  } else {
    echo "Registration failed.  Please try again.";
  }
}
?>
