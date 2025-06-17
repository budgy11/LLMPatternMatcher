    Username: <input type="text" name="username" required>
    Password: <input type="password" name="password" required>
    <button type="submit">Login</button>
  </form>

</body>
</html>


<?php

// Database credentials - Replace with your actual credentials
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database";

// Check if the login form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // Get username and password from the form
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Validate input (basic example - improve for production)
  if (empty($username) || empty($password)) {
    $error_message = "Username and password cannot be empty.";
  } else {
    // Database connection
    $conn = new mysqli($host, $username, $password, $database);

    // Check connection
    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }

    // SQL query to select the user
    $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
    $result = $conn->query($sql);

    // Check if a user was found
    if ($result->num_rows > 0) {
      // User found - set session variables for authentication
      session_start();
      $_SESSION["username"] = $username;
      $_SESSION["user_id"] = $result->fetch_assoc()["id"]; // Assuming your users table has an 'id' column

      // Redirect to a secure area or home page
      header("Location: secure_page.php"); // Replace with your secure page
      exit();
    } else {
      $error_message = "Incorrect username or password.";
    }
  }
}
?>
