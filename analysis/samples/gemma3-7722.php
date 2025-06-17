
<!DOCTYPE html>
<html>
<head>
  <title>Shopping Cart</title>
</head>
<body>

  <h1>Products</h1>
  <form method="post">
    <?php
    foreach ($products as $id => $product) {
      echo "<div>";
      echo "<strong>" . $product['name'] . " - $" . $product['price'] . "</strong><br>";
      echo "<input type='number' name='product_id[]' value='" . $id . "' min='1' style='width:50px;'>";
      echo "<button type='submit' name='add_to_cart'>Add to Cart</button> | ";
      echo "<button type='submit' name='update_quantity' style='background-color:red;'>Update Quantity</button>  | ";
      echo "<button type='submit' name='remove_from_cart' style='background-color:red;'>Remove from Cart</button>";
      echo "</div>";
    }
    ?>
