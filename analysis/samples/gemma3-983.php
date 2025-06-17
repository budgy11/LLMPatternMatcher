    Username: <input type="text" name="username" required><br><br>
    Password: <input type="password" name="password" required><br><br>
    <input type="submit" value="Login">
  </form>

  <br>
  <a href="register.php">Don't have an account? Register here.</a>

</body>
</html>


<?php
// ... (Previous code) ...

// Login function (with parameterized query)
function login($username, $password) {
  // Connect to the database
  $conn = new mysqli($host, $username, $password, $database);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // SQL query with a placeholder
  $sql = "SELECT * FROM users WHERE username = ? AND password = ?";

  // Prepare the statement
  $stmt = $conn->prepare($sql);

  // Bind the parameters
  $stmt->bind_param("ss", $username, $password); // "ss" means two strings

  // Execute the statement
  $result = $stmt->execute();

  if ($result) {
    $row = $stmt->fetch_assoc();
    $_SESSION['username'] = $row['username'];
    $_SESSION['user_id'] = $row['id'];
    return true;
  } else {
    return false;
  }

  $stmt->close(); // Close the statement
  $conn->close();
}
?>
