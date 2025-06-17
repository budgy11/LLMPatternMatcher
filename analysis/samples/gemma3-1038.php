
<!DOCTYPE html>
<html>
<head>
  <title>Register</title>
</head>
<body>

  <h2>Register</h2>

  <?php if (isset($error_message)) {
    echo "<p style='color: red;'>$error_message</p>";
  }
  ?>
