    <br><br>

    <button type="submit">Add to Cart</button>
</form>


<?php
session_start(); // Start the session

// Function to add an item to the cart
function addToCart($product_id, $product_name, $price, $quantity) {
  if (empty($_SESSION['cart'])) {
    // Cart is empty, create a new cart array
    $_SESSION['cart'] = array();
  }

  // Add the item to the cart array
  $_SESSION['cart'][] = array(
    'product_id' => $product_id,
    'product_name' => $product_name,
    'price' => $price,
    'quantity' => $quantity
  );

  // Optionally, you could also update the quantity if the item already exists
  // This example just adds new items.
}

// Example Usage:
// Let's say we have these product details:
$product1_id = 1;
$product1_name = "T-Shirt";
$product1_price = 20.00;

$product2_id = 2;
$product2_name = "Jeans";
$product2_price = 50.00;

// Add product 1 to the cart (quantity of 2)
addToCart($product1_id, $product1_name, $product1_price, 2);

// Add product 2 to the cart (quantity of 1)
addToCart($product2_id, $product2_name, $product2_price, 1);


// Display the contents of the cart
echo "<h2>Your Shopping Cart</h2>";

if (!empty($_SESSION['cart'])) {
  echo "<ul>";
  foreach ($_SESSION['cart'] as $item) {
    echo "<li>" . $item['product_name'] . " - $" . $item['price'] . " x " . $item['quantity'] . " = $" . ($item['price'] * $item['quantity']) . "</li>";
  }
  echo "</ul>";

  // Calculate the total price
  $total = 0;
  foreach ($_SESSION['cart'] as $item) {
    $total += $item['price'] * $item['quantity'];
  }

  echo "<p><strong>Total: $" . $total . "</strong></p>";

} else {
  echo "<p>Your cart is empty.</p>";
}


// Example of removing an item from the cart (optional)
// Remove the item with product_id = 1
if (isset($_GET['remove'])) {
    $product_id_to_remove = $_GET['remove'];

    // Find the item in the cart and remove it
    $key = array_search($product_id_to_remove, 0, count($_SESSION['cart']));

    if ($key !== false) {
        unset($_SESSION['cart'][$key]);
    }
}

?>
