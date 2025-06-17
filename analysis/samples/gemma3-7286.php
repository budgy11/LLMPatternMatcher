
<!DOCTYPE html>
<html>
<head>
  <title>Purchase Functionality</title>
  <style>
    body { font-family: sans-serif; }
    .container { max-width: 600px; margin: 0 auto; padding: 20px; }
    .error { color: red; }
    .success { color: green; }
  </style>
</head>
<body>
  <div class="container">
    <h1>Purchase Product</h1>

    <?php if (isset($error)) { echo "<p class='error'>" . $error . "</p>"; } ?>
