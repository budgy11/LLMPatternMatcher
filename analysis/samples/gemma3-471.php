    Username: <input type="text" name="username" required><br><br>
    Password: <input type="password" name="password" required><br><br>
    <button type="submit">Login</button>
</form>

</body>
</html>


<?php

// Database configuration (replace with your actual values)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_db_user";
$db_password = "your_db_password";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get username and password from the form
  $username = trim($_POST["username"]);
  $password = trim($_POST["password"]);

  // Validate input (important for security!)
  if (empty($username) || empty($password)) {
    $error = "Username and password cannot be empty.";
  } elseif (!filter_var($username, FILTER_VALIDATE_EMAIL) && !preg_match('/^[a-zA-Z0-9_]+$/', $username)) {
     $error = "Invalid username.  Username must be a valid email address or contain only letters, numbers, and underscores.";
  }  else {
    // Hash the password (VERY IMPORTANT for security)
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Query the database
    $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }

    // Prepare the query
    $sql = "SELECT id, username, password FROM users WHERE username = '$username'";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
      $stmt->execute();
      $result = $stmt->get_result();

      if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row["password"])) {
          // Authentication successful!
          // Set session variables to keep the user logged in
          session_start();
          $_SESSION["user_id"] = $row["id"];
          $_SESSION["username"] = $row["username"];
          echo "Login successful!";
          // Redirect the user to a welcome page or desired location
          header("Location: welcome.php"); // Replace with your welcome page
          exit();
        } else {
          echo "Incorrect password.";
        }
      } else {
        echo "User not found.";
      }

      $stmt->close();
    } else {
      echo "Error preparing statement.";
    }
  }
}
?>
