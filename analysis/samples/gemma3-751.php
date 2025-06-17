
<!DOCTYPE html>
<html>
<head>
  <title>Login Form</title>
</head>
<body>

  <h2>Login</h2>

  <form method="post" action="">
    Username: <input type="text" name="login_username" placeholder="Enter your username"><br><br>
    Password: <input type="password" name="login_password" placeholder="Enter your password"><br><br>
    <input type="submit" name="login_submit" value="Login">
  </form>

</body>
</html>


<?php

// Example using prepared statements for secure SQL queries
function loginUser($username, $password) {
  // 1. Connect to the database
  $conn = new mysqli($host, $username, $password, $database);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // 2. Prepare the SQL query using prepared statements
  $sql = "SELECT * FROM users WHERE username = ? AND password = ?";
  $stmt = $conn->prepare($sql);

  // Bind the parameters
  $stmt->bind_param("ss", $username, $password);  // "ss" indicates two string parameters

  // Execute the query
  $result = $stmt->execute();

  if ($result) {
    $user = $stmt->fetch_assoc();
    // 4. Store user session information
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['username'] = $user['username'];

    return true;
  } else {
    return false;
  }

  // Close connection
  $conn->close();
}

// Example usage (same as before)
if (isset($_POST['login_username']) && isset($_POST['login_password'])) {
  $username = $_POST['login_username'];
  $password = $_POST['login_password'];

  if (loginUser($username, $password)) {
    header("Location: protected_page.php");
    exit();
  } else {
    echo "<p style='color:red;'>Invalid username or password.</p>";
  }
}
?>
