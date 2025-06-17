
<!DOCTYPE html>
<html>
<head>
  <title>User Login</title>
</head>
<body>

  <h2>Login</h2>

  <?php if (isset($error_message)) {
    echo "<p style='color: red;'>$error_message</p>";
  }
?>
