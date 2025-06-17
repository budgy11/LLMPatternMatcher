
<!DOCTYPE html>
<html>
<head>
  <title>Login</title>
</head>
<body>

  <h1>Login</h1>

  <form method="post" action="">
    <label for="username">Username:</label>
    <input type="text" id="username" name="username">

    <label for="password">Password:</label>
    <input type="password" id="password" name="password">

    <button type="submit">Login</button>
  </form>

  <?php if (isset($error)) {
    echo "<p style='color: red;'>$error</p>";
  } ?>
