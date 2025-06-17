
<!DOCTYPE html>
<html>
<head>
  <title>User Registration</title>
</head>
<body>

  <h1>Register</h1>

  <?php if (isset($errors)) {
    echo "<div style='color: red;'>Error: " . $errors . "</div>";
  } ?>
