    Username: <input type="text" name="username" required><br><br>
    Password: <input type="password" name="password" required><br><br>
    <input type="submit" value="Login">
  </form>

</body>
</html>


<?php

// Database credentials (replace with your actual values)
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "your_database_name";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // Get the username and password from the form
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Connect to the database
  $conn = new mysqli($servername, $username, $password, $dbname);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // Prepare the SQL query
  $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";  // Sanitize input if needed

  // Execute the query
  $result = $conn->query($sql);

  // Check if any rows were returned
  if ($result->num_rows > 0) {
    // User found, set a session variable to indicate login
    session_start();
    $_SESSION["username"] = $username;
    echo "Login successful!";
    // Redirect to a protected page or home page
    header("Location: /protected_page.php"); // Replace with your desired destination.  For example, a specific page that requires login
    exit();

  } else {
    echo "Incorrect username or password.";
  }

  // Close the connection
  $conn->close();
}

?>
