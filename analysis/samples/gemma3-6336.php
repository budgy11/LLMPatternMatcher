
<!DOCTYPE html>
<html>
<head>
  <title>Shopping Cart</title>
</head>
<body>

  <h1>Shopping Cart</h1>

  <!-- Product Listing (replace with your product listing logic) -->
  <h2>Available Products</h2>
  <ul>
    <?php
    foreach ($products as $item_id => $product) {
      echo "<li>" . $product['name'] . " - $" . number_format($product['price'], 2) . "<br>";
      echo "<form method='post' action='cart.php'>
                <label for='quantity_" . $item_id . "'>Quantity:</label>
                <input type='number' id='quantity_" . $item_id . "' name='quantity' value='1' min='1'>
                <input type='hidden' name='id' value='" . $item_id . "'>
                <input type='submit' value='Add to Cart'>
              </form>
            </li>";
    }
    ?>
