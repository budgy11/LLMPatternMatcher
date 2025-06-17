
<!DOCTYPE html>
<html>
<head>
  <title>User Login</title>
</head>
<body>

  <h2>User Login</h2>

  <form method="post" action="">
    <label for="username">Username:</label>
    <input type="text" id="username" name="username">
    <br><br>
    <label for="password">Password:</label>
    <input type="password" id="password" name="password">
    <br><br>
    <button type="submit">Login</button>
  </form>

  <?php if (isset($error)) {
    echo "<p style='color:red;'>$error</p>";
  } ?>
