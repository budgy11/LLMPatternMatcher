
<!DOCTYPE html>
<html>
<head>
  <title>User Registration</title>
</head>
<body>

  <h2>Registration Form</h2>

  <?php if (isset($error)) {
    echo "<p style='color: red;'>$error</p>";
  } ?>
