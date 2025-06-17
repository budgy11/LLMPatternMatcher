    Username: <input type="text" name="username" required><br><br>
    Password: <input type="password" name="password" required><br><br>
    <button type="submit">Login</button>
  </form>

</body>
</html>


<?php

// Database connection details - Replace with your actual credentials
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_database_user";
$db_password = "your_database_password";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get username and password from the form
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Validate input (Important for security!)
  if (empty($username) || empty($password)) {
    $error_message = "Username and Password must be filled.";
  } else {
    // Hash the password for security (Never store passwords in plain text!)
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Attempt to authenticate the user
    $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
      $user = $result->fetch_assoc();
      if (password_verify($password, $user["password"])) {
        // Authentication successful - set session variables
        session_start();
        $_SESSION["user_id"] = $user["id"];
        $_SESSION["username"] = $username;
        echo "Login successful! <a href='home.php'>Go to Home</a>";
      } else {
        $error_message = "Incorrect password.";
      }
    } else {
      $error_message = "Invalid username.";
    }
  }
}
?>
