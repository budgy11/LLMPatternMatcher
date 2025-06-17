
<!DOCTYPE html>
<html>
<head>
  <title>Shopping Cart</title>
</head>
<body>

  <h1>Shopping Cart</h1>

  <?php if (isset($success_message)) {
    echo "<p style='color: green;'>$success_message</p>";
  } else if (isset($error_message)) {
    echo "<p style='color: red;'>$error_message</p>";
  } ?>
