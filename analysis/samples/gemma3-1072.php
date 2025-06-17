    Username: <input type="text" name="username" required><br><br>
    Password: <input type="password" name="password" required><br><br>
    <input type="submit" value="Login">
  </form>

  <p>
    Don't have an account? <a href="register.php">Register here</a>.
  </p>

</body>
</html>


<?php
// ... (database connection details)

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Prepare the SQL query
  $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? AND password = ?");

  // Bind parameters
  $stmt->bind_param("ss", $username, $password);  // "ss" indicates two string parameters

  // Execute the query
  if ($stmt->execute() === TRUE) {
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
      $user = $result->fetch_assoc();
      session_start();
      $_SESSION["username"] = $username;
      header("Location: /welcome.php");
      exit();
    } else {
      $error = "Invalid username or password.";
    }
  } else {
    $error = "Database error: " . $stmt->error;
  }
}
?>
