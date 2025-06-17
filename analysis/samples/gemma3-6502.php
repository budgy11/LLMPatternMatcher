
<!-- HTML Form to add items to the cart -->
<form method="post" action="">
  <h2>Add to Cart</h2>
  <?php
  foreach ($products as $product_id => $product) {
    echo "<label for='" . $product_id . "'>" . $product['name'] . " - $" . $product['price'] .  "</label><br>";
    echo "<input type='number' id='" . $product_id . "' name='product_id' value='" . $product_id . "' min='1'  size='3'><br>"; //Use the product id as an input for the form
  }
  ?>
