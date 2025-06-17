    Username: <input type="text" name="username" required><br><br>
    Password: <input type="password" name="password" required><br><br>
    <input type="submit" value="Login">
  </form>

</body>
</html>


<?php

// Database credentials (replace with your actual values)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_database_user";
$db_password = "your_database_password";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get the username and password from the form
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Validate input (IMPORTANT - Add more robust validation in a real application)
  if (empty($username) || empty($password)) {
    $error = "Username and Password cannot be empty.";
  } elseif (validatePassword($password)) {
    // Hash the password before comparing
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Query the database to check the credentials
    $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
      $user = $result->fetch_assoc();
      // Verify password
      if (password_verify($password, $user["password"])) {
        // Login successful - Set session variables
        session_start();
        $_SESSION["user_id"] = $user["id"];
        $_SESSION["username"] = $user["username"];
        echo "Login successful! <a href='dashboard.php'>Go to Dashboard</a>";
      } else {
        $error = "Incorrect password.";
      }
    } else {
      $error = "Invalid username.";
    }
  } else {
    $error = "Invalid password format.";
  }
}

// Helper function for password validation (improve this!)
function validatePassword($password) {
  return (strlen($password) >= 8); // Minimum 8 characters
}

// Start the database connection
$conn = new mysqli($db_host, $db_user, $db_password, $db_name);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

?>
