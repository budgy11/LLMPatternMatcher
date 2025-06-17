    <button type="submit">Login</button>
  </form>

</body>
</html>


<?php

// Database credentials (replace with your actual credentials)
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database_name";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get username and password from the form
  $username = trim($_POST["username"]);
  $password = trim($_POST["password"]);

  // Validate input (important for security!)
  if (empty($username) || empty($password)) {
    $error = "Username and password cannot be empty.";
  } else {
    // Database connection
    $conn = new mysqli($host, $username, $password, $database);

    // Check connection
    if ($conn->connect_error) {
      $error = "Connection failed: " . $conn->connect_error;
    } else {
      // Prepare and execute the query
      $stmt = $conn->prepare("SELECT username, password FROM users WHERE username = ?");
      $stmt->bind_param("s", $username); // "s" indicates a string
      $stmt->execute();
      $result = $stmt->get_result();

      if ($result->num_rows == 0) {
        $error = "Invalid username or password.";
      } else {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row["password"])) {
          // Password matches!  Set session variables
          session_start();
          $_SESSION["username"] = $username;
          $error = "Login successful!";
        } else {
          $error = "Incorrect password.";
        }
      }
    }
  }
}
?>
