
<!DOCTYPE html>
<html>
<head>
  <title>User Registration</title>
</head>
<body>

  <h2>Register</h2>

  <?php if (isset($errors)) {
    echo "<div style='color: red;'>$errors</div>";
  }
  ?>
