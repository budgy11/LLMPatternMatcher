

<?php
session_start();

// Cart data (in a real application, this would come from a database)
$cart = [];

// Helper function to add items to the cart
function addToCart($product_id, $quantity) {
  global $cart;

  if (isset($cart[$product_id])) {
    $cart[$product_id]['quantity'] += $quantity;
  } else {
    $cart[$product_id] = ['quantity' => $quantity];
  }
}

// Example: Add some items to the cart
addToCart(1, 2);  // Add 2 of product ID 1
addToCart(2, 1);  // Add 1 of product ID 2
addToCart(1, 3);  // Add 3 of product ID 1

// Display the cart contents (for demonstration)
echo "<h2>Your Shopping Cart</h2>";

if (empty($cart)) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($cart as $product_id => $item) {
    // Simulate getting product details (replace with database lookup)
    $product_name = "Product " . $product_id;
    $product_price = $product_id * 10;  //  Example price

    echo "<li>" . $product_name . " - Quantity: " . $item['quantity'] . " - Price: $" . $product_price . "</li>";
  }
  echo "</ul>";
}

// Example:  Remove an item
if (isset($_GET['remove'])) {
  $remove_id = $_GET['remove'];

  if (isset($cart[$remove_id])) {
    unset($cart[$remove_id]);
  }

  // Redirect back to the cart page
  header("Location: cart.php");
  exit();
}


// Example: Update quantity (via a form)
if (isset($_POST['update_quantity'])) {
  $product_id = $_POST['product_id'];
  $new_quantity = $_POST['quantity'];

  if (isset($cart[$product_id])) {
    $cart[$product_id]['quantity'] = $new_quantity;
  }

  // Redirect back to the cart page
  header("Location: cart.php");
  exit();
}
?>
