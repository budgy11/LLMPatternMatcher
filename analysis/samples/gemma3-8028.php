
<!DOCTYPE html>
<html>
<head>
  <title>Purchase Functionality</title>
</head>
<body>

  <h2>Purchase Product</h2>

  <?php if (isset($error)) {
    echo "<p style='color: red;'>Error: " . $error . "</p>";
  }
  ?>
