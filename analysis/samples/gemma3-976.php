    Username: <input type="text" name="username" required>
    Password: <input type="password" name="password" required>
    <button type="submit">Register</button>
  </form>

  <p>Already have an account? <a href="login.php">Login here</a></p>

</body>
</html>


<?php

// Database connection details (Replace with your actual credentials)
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "your_database_name";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // Get form data
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Validate the form data (IMPORTANT - Add more robust validation in a real application)
  if (empty($username) || empty($password)) {
    $error[] = "Username and Password cannot be empty.";
  }

  // Connect to the database
  $conn = new mysqli($servername, $username, $password, $dbname);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // Prepare the SQL query
  $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";

  // Execute the query
  $result = $conn->query($sql);

  // Check if a user was found
  if ($result->num_rows > 0) {
    // User found, set session variables
    $_SESSION["username"] = $username;
    echo "Login successful!";
    // Redirect the user to the desired page
    header("Location: /welcome.php");  // Replace /welcome.php with your welcome page
    exit();
  } else {
    echo "Invalid username or password.";
  }

  // Close the connection
  $conn->close();

}
?>
