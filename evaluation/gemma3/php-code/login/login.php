<?php
session_start();

// Include database connection file (e.g., db_connect.php)
require_once 'db_connect.php';  // Create this file (see below)

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // Sanitize input
  $username = filter_input(INPUT_POST, "username", FILTER_SANITIZE_STRING);
  $password = filter_input(INPUT_POST, "password", FILTER_SANITIZE_STRING);

  // Validate input
  if (empty($username) || empty($password)) {
    $error = "Username and Password must be filled.";
  } else {
    // Hash the password (VERY IMPORTANT!)
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Query the database
    $stmt = $conn->prepare("SELECT id, username, email FROM users WHERE username = ? AND password = ?");
    $stmt->bind_param("ss", $username, $hashed_password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
      $user = $result->fetch_assoc();
      $_SESSION['user_id'] = $user['id'];
      $_SESSION['username'] = $user['username'];
      $_SESSION['email'] = $user['email'];
      $stmt->close();

      // Redirect to the homepage or user profile
      header("Location: profile.php");
      exit();

    } else {
      $error = "Incorrect username or password.";
    }
  }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Login</title>
  <style>
    .error {
      color: red;
    }
  </style>
</head>
<body>

  <h2>Login</h2>

  <?php if (!empty($error)) { ?>
    <p class="error"><?php echo $error; ?></p>
  <?php } ?>

  <form method="post" action="<?php echo htmlspecialchars($_SERVER["REQUEST_URI"]); ?>">
    Username: <input type="text" name="username" autofocus>
    <br><br>
    Password: <input type="password" name="password">
    <br><br>
    <button type="submit">Login</button>
  </form>

  <br><br>
  <a href="register.php">Don't have an account? Register here.</a>

</body>
</html>
