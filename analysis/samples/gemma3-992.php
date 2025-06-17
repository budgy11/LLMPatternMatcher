    <br><br>
    Password:
    <input type="password" name="password">
    <br><br>
    <button type="submit">Login</button>
  </form>

  <br><br>
  <a href="register.php">Don't have an account? Register here.</a>

</body>
</html>


<?php

// Database configuration (Replace with your actual values)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_db_user";
$db_pass = "your_db_password";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get user input
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Validate input (IMPORTANT:  Add more validation here!)
  if (empty($username) || empty($password)) {
    $error = "Username and Password cannot be empty.";
  } else {
    // Database connection
    $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

    // Check connection
    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }

    // SQL query (Use prepared statements for security - see explanation below)
    $sql = "SELECT id, username, password FROM users WHERE username = '$username'";

    // Execute the query
    $result = $conn->query($sql);

    if ($result->num_rows == 0) {
      $error = "Invalid username or password.";
    } else {
      // Authentication successful
      $row = $result->fetch_assoc();

      // Password verification (VERY IMPORTANT: Use password_verify)
      if (password_verify($password, $row["password"])) {
        // Set session variables
        session_start();
        $_SESSION["user_id"] = $row["id"];
        $_SESSION["username"] = $username;
        
        // Redirect to a secure page or home page
        header("Location: secure_page.php"); // Replace with your secure page
        exit();
      } else {
        $error = "Incorrect password.";
      }
    }
  }
}
?>
