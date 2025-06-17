

<?php
session_start();

// Assuming you have a function to handle adding items to the cart
function addToCart($product_id, $quantity) {
  // In a real application, you would likely interact with a database here
  // to get product details and update the cart.
  // This is just a simplified example.

  if (isset($_SESSION['cart']) === false) {
    $_SESSION['cart'] = array();
  }

  if (isset($_SESSION['cart'][$product_id]) === true) {
    $_SESSION['cart'][$product_id]['quantity'] += $quantity;
  } else {
    $_SESSION['cart'][$product_id] = array('quantity' => $quantity);
  }
}

// Example usage:  Adding an item to the cart
$productId = 123; // Example product ID
$quantity = 2;

addToCart($productId, $quantity);

// Display the cart contents (for demonstration)
echo "<h2>Your Cart</h2>";
if (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0) {
  echo "<ul>";
  foreach ($_SESSION['cart'] as $product_id => $item) {
    echo "<li>Product ID: $product_id - Quantity: $item['quantity']</li>";
  }
  echo "</ul>";
} else {
  echo "<p>Your cart is empty.</p>";
}

// Example: Removing an item from the cart (optional)
// This is a simplified example and doesn't handle errors or other complexities.
// In a real application, you would need to handle these cases more robustly.

// echo "<br><br>Removing Item <br>";
// if (isset($_GET['remove'])) {
//    $productIdToRemove = $_GET['remove'];
//    if (isset($_SESSION['cart'][$productIdToRemove]) ) {
//      unset($_SESSION['cart'][$productIdToRemove]);
//      if (empty($_SESSION['cart'])) {
//         unset($_SESSION['cart']);
//      }
//    }
// }
?>
