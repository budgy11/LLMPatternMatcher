    <br />
    <button type="submit">Login</button>
  </form>

  <br>
  <a href="register.php">Don't have an account? Register here.</a>

</body>
</html>


<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST["username"];
  $password = $_POST["password"];

  if (empty($username) || empty($password)) {
    $error_message = "Username and password cannot be empty.";
  } else {
    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // SQL Query
    $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }

    if (mysqli_stmt_prepare($conn, "INSERT INTO users (username, password) VALUES (?, ?)")) {
      if (mysqli_stmt_bind_param($conn, "ss", $username, $hashed_password)) {
        mysqli_stmt_execute($conn);
        mysqli_stmt_close($conn);

        // Login successful - redirect to login.php
        header("Location: login.php");
        exit();
      } else {
        $error_message = "Error preparing statement.";
      }
    } else {
      $error_message = "Error preparing statement.";
    }
  }
}
?>
