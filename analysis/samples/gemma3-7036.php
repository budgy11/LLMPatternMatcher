
<!DOCTYPE html>
<html>
<head>
  <title>Purchase Product</title>
</head>
<body>

  <h1>Purchase Product</h1>

  <?php
  // Display any error messages from the session
  if (isset($_SESSION["errors"])) {
    echo "<h3>Errors:</h3>";
    echo "<ul>";
    foreach ($_SESSION["errors"] as $field => $error) {
      echo "<li>" . $field . ": " . $error . "</li>";
    }
    echo "</ul>";
    unset($_SESSION["errors"]); // Remove errors from session
  }
  ?>
