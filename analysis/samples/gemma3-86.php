    Username: <input type="text" name="username">
    <br><br>
    Password: <input type="password" name="password">
    <br><br>
    <button type="submit">Login</button>
  </form>

  <br><br>
  <a href="register.php">Not a user? Register here.</a>  <!-- Add a link to registration -->

</body>
</html>


<?php
// ... (Database connection code as before) ...

// Function to handle user login
function login_user($username, $password) {
  // ... (Database connection code) ...

  // SQL query to authenticate the user
  $sql = "SELECT * FROM users WHERE username = '$username' AND password = PASSWORD('$password')";

  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    $user_data = $result->fetch_assoc();
    session_start();

    // Hash the password for storage
    $hashed_password = password_hash($password, PASSWORD_DEFAULT); // Use PASSWORD_DEFAULT for the best hashing algorithm

    $_SESSION['user_id'] = $user_data['id'];
    $_SESSION['username'] = $user_data['username'];

    return true;
  } else {
    return false;
  }

  $conn->close();
}

// Example usage
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST["username"];
  $password = $_POST["password"];

  if (validate_input($username, $password)) {
    if (login_user($username, $password)) {
      header("Location: welcome.php");
      exit();
    } else {
      $error = "Invalid username or password.";
    }
  } else {
    $error = "Please enter both username and password.";
  }
}
?>
