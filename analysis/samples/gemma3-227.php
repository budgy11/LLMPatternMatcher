
<!DOCTYPE html>
<html>
<head>
  <title>User Login</title>
</head>
<body>

  <h2>User Login</h2>

  <form method="post" action="">
    Username: <input type="text" name="username" required><br><br>
    Password: <input type="password" name="password" required><br><br>
    <input type="submit" value="Login">
  </form>

</body>
</html>


<?php

// ... (Database credentials) ...

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST["username"];
  $password = $_POST["password"];

  $conn = new mysqli($servername, $username, $password, $dbname);

  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // Use a prepared statement
  $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? AND password = ?");
  $stmt->bind_param("ss", $username, $password); // "ss" indicates two strings
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows == 1) {
    $user_row = $result->fetch_assoc();
    session_start();
    $_SESSION["username"] = $user_row["username"];
    $_SESSION["user_id"] = $user_row["id"];
    header("Location: secure_page.php");
    exit;
  } else {
    echo "Invalid username or password.";
  }

  $stmt->close();
  $conn->close();
}
?>
