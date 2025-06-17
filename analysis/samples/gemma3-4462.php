
<!DOCTYPE html>
<html>
<head>
  <title>User Registration</title>
</head>
<body>

  <h2>User Registration</h2>

  <?php if (isset($errorMessage)) {
    echo "<p style='color: red;'>Error: " . $errorMessage . "</p>";
  } ?>
