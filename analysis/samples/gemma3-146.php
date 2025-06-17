    Username: <input type="text" name="username" required>
    Password: <input type="password" name="password" required>
    <button type="submit">Login</button>
  </form>

  <p>Don't have an account? <a href="register.php">Register</a></p>

</body>
</html>


<?php
// ... (database connection)

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // ... (input validation)

  $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE username = ?");
  $stmt->bind_param("s", $username); // "s" indicates a string parameter

  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    if (password_verify($password, $row["password"])) {
      // ... (login logic)
    } else {
      $error_message = "Incorrect password.";
    }
  } else {
    $error_message = "Username not found.";
  }

  $stmt->close();
}
?>
