
<!DOCTYPE html>
<html>
<head>
  <title>User Login</title>
</head>
<body>

  <h2>Login</h2>

  <form method="post" action="">
    <label for="login_username">Username:</label>
    <input type="text" id="login_username" name="login_username">
    <br><br>

    <label for="login_password">Password:</label>
    <input type="password" id="login_password" name="login_password">
    <br><br>

    <button type="submit" name="login_submit">Login</button>
  </form>

</body>
</html>


<?php

// ... (Database connection code) ...

  // SQL query with placeholders
  $sql = "SELECT * FROM users WHERE username = ? AND password = ?";
  $stmt = $conn->prepare($sql);

  if ($stmt === false) {
    die("Error preparing statement: " . $conn->error);
  }

  // Bind the parameters
  $stmt->bind_param("ss", $username, $password);  // "ss" indicates two string parameters

  // Execute the query
  $stmt->execute();

  // Get the result
  $result = $stmt->get_result();

  // ... (Rest of the login logic) ...

  $stmt->close();
?>
