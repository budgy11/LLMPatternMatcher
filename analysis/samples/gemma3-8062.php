
<!DOCTYPE html>
<html>
<head>
  <title>Purchase Functionality</title>
</head>
<body>

  <h1>Purchase Functionality</h1>

  <?php if ($result == "Purchase successful! Order ID: ") {
    echo "<p>" . $result . "</p>";
  } elseif ($result == "Cart is empty.  Cannot process purchase.") {
    echo "<p>" . $result . "</p>";
  } elseif ($result == "Invalid cart_id or user_id.  Both must be numeric.") {
    echo "<p>" . $result . "</p>";
  } else {
    echo "<p>" . $result . "</p>";
  }
?>
