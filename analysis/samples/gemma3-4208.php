
<!DOCTYPE html>
<html>
<head>
  <title>User Registration</title>
</head>
<body>

  <h2>User Registration</h2>

  <?php
  // Display any error messages
  if (!empty($email_error)) {
    echo "<p style='color: red;'>$email_error</p>";
  }
  if (!empty($password_error)) {
    echo "<p style='color: red;'>$password_error</p>";
  }
  if (!empty($username_error)) {
    echo "<p style='color: red;'>$username_error</p>";
  }
  ?>
