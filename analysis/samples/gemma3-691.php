    <button type="submit">Login</button>
  </form>

</body>
</html>


<?php

// Database Credentials - Replace with your actual credentials
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_database_user";
$db_password = "your_database_password";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // Get username and password from the form
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Validate the inputs (Important for security)
  if (empty($username) || empty($password)) {
    $error = "Username and password cannot be empty.";
  } elseif (!filter_var($username, FILTER_VALIDATE_EMAIL)) {
    $error = "Invalid email address.";
  } else {
    // SQL Query (Using prepared statements for security)
    $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
      $user = $result->fetch_assoc();
      // Verify password (Important!) - compare the hashed password
      if (password_verify($password, $user["password"])) {
        // Login successful - Set session variables
        session_start();
        $_SESSION["user_id"] = $user["id"];
        $_SESSION["username"] = $user["username"];
        echo "Login successful! <a href='welcome.php'>Welcome, " . htmlspecialchars($user["username"]) . "</a>";
      } else {
        $error = "Incorrect password.";
      }
    } else {
      $error = "Incorrect username.";
    }
  }
}
?>
