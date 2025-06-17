

<?php
session_start();

// Cart data (in a real application, this would come from a database)
$cart = [];

// --- Helper Functions ---

// Add item to cart
function add_to_cart($product_id, $product_name, $price, $quantity = 1) {
  global $cart;

  if (isset($cart[$product_id])) {
    $cart[$product_id]['quantity'] += $quantity;
  } else {
    $cart[$product_id] = [
      'name' => $product_name,
      'price' => $price,
      'quantity' => $quantity,
    ];
  }
}

// Remove item from cart
function remove_from_cart($product_id) {
  global $cart;

  if (isset($cart[$product_id])) {
    unset($cart[$product_id]);
  }
}

// Update quantity of item in cart
function update_quantity($product_id, $new_quantity) {
  global $cart;

  if (isset($cart[$product_id])) {
    $cart[$product_id]['quantity'] = $new_quantity;
  }
}

// Calculate cart total
function calculate_cart_total() {
  global $cart;
  $total = 0;
  foreach ($cart as $item) {
    $total += $item['price'] * $item['quantity'];
  }
  return $total;
}

// Display Cart Contents
function display_cart() {
  global $cart;

  if (empty($cart)) {
    echo "<p>Your cart is empty.</p>";
    return;
  }

  echo "<h2>Your Shopping Cart</h2>";
  echo "<ul>";
  foreach ($cart as $product_id => $item) {
    echo "<li>";
    echo "<strong>" . $item['name'] . "</strong> - $" . $item['price'] . " x " . $item['quantity'] . " = $" . ($item['price'] * $item['quantity']) . "</li>";
    echo "<form method='post' action='" . htmlspecialchars($_SERVER["PHP_SELF"]) . "'>";
    echo "<input type='hidden' name='product_id' value='" . htmlspecialchars($product_id) . "'>";
    echo "<input type='submit' value='Remove from Cart'>";
    echo "</form>";
  }
  echo "</ul>";
  echo "<p><strong>Total: $" . calculate_cart_total() . "</strong></p>";
}



// --- Example Usage (Simulate User Actions) ---

// 1. Add an item to the cart
add_to_cart(1, "T-Shirt", 20.00, 2);
add_to_cart(2, "Jeans", 50.00, 1);

// 2. Update quantity of an item
update_quantity(1, 3); // Increase quantity of T-Shirt to 3

// 3. Remove an item
//remove_from_cart(2); // Remove Jeans

// 4. Display the cart
display_cart();

// --- Session Management ---

// You'd typically handle adding items to the cart based on user interaction
// (e.g., clicking a "Add to Cart" button).  The following is a placeholder
// for how you *might* do that within a real application:

// Example:  Simulating a user adding an item to the cart (e.g., on a product page)
/*
if (isset($_POST['add_to_cart'])) {
  $product_id = $_POST['product_id'];
  $product_name = $_POST['product_name'];
  $price = $_POST['price'];
  $quantity = $_POST['quantity'];

  add_to_cart($product_id, $product_name, $price, $quantity);
  // Optionally, redirect to the cart page
  // header("Location: cart.php");
  // exit;
}
*/


?>
