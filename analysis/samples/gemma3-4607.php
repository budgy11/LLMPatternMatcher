
<!DOCTYPE html>
<html>
<head>
  <title>User Registration</title>
  <style>
    .error {
      color: red;
    }
    .success {
      color: green;
    }
  </style>
</head>
<body>

  <h2>User Registration</h2>

  <?php if (isset($error)) { echo "<p class='error'>" . $error . "</p>"; } ?>
