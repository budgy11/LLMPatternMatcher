
<!DOCTYPE html>
<html>
<head>
  <title>Login</title>
</head>
<body>

  <h1>Login</h1>

  <?php
  if (isset($error_message)) {
    echo "<p style='color:red;'>$error_message</p>";
  }
  ?>
