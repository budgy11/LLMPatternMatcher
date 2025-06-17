
<!DOCTYPE html>
<html>
<head>
  <title>User Registration</title>
</head>
<body>

  <h2>Register</h2>

  <?php if (isset($username_error)) echo "<p style='color:red;'>$username_error</p>";
       if (isset($email_error)) echo "<p style='color:red;'>$email_error</p>";
       if (isset($password_error)) echo "<p style='color:red;'>$password_error</p>";?>
