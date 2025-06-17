
<!DOCTYPE html>
<html>
<head>
  <title>Shopping Cart</title>
</head>
<body>

  <h1>Shopping Cart</h1>

  <form method="post" action="">
    <h2>Add to Cart</h2>
    <?php
    foreach ($products as $product) {
      echo "<label for='" . $product['id'] . "'>" . $product['name'] . " ($" . $product['price'] . ")</label><br>";
      echo "<input type='number' id='" . $product['id'] . "' name='product_id' value='" . $product['id'] . "' min='1'><br>";
    }
    ?>
