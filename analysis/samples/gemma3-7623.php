

<!DOCTYPE html>
<html>
<head>
  <title>Purchase Functionality</title>
</head>
<body>

  <h1>Make a Purchase</h1>

  <?php if (isset($error_message)) {
    echo "<p style='color:red;'>$error_message</p>";
  } ?>
