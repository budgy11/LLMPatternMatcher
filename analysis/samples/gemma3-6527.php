

<?php
session_start();

// Initialize the cart as an empty array if it doesn't exist
if (!isset($_SESSION['cart'])) {
  $_SESSION['cart'] = array();
}

// Function to add an item to the cart
function addToCart($productId, $productName, $price, $quantity = 1) {
  $_SESSION['cart'][] = array(
    'id' => $productId,
    'name' => $productName,
    'price' => $price,
    'quantity' => $quantity
  );
}

// Function to remove an item from the cart
function removeCartItem($productId) {
  $cart = $_SESSION['cart'];

  // Iterate through the cart and remove the item with the matching ID
  foreach ($cart as $index => $item) {
    if ($item['id'] == $productId) {
      unset($_SESSION['cart'][$index]);
      // Re-index the array after deleting an element to avoid gaps
      $_SESSION['cart'] = array_values($_SESSION['cart']);
      return true;
    }
  }

  return false; // Item not found
}

// Function to update the quantity of an item in the cart
function updateCartQuantity($productId, $quantity) {
  $cart = $_SESSION['cart'];

  // Iterate through the cart and find the item to update
  foreach ($cart as $index => $item) {
    if ($item['id'] == $productId) {
      $cart[$index]['quantity'] = $quantity;
      return true;
    }
  }

  return false; // Item not found
}


// Example Usage:  (This is just for demonstration)

// Add a product to the cart
addToCart(1, "Laptop", 1200, 1);
addToCart(2, "Mouse", 25, 2);


// Display the cart contents
echo "<h2>Your Cart</h2>";
if (empty($_SESSION['cart'])) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($_SESSION['cart'] as $item) {
    echo "<li>" . $item['name'] . " - $" . $item['price'] . " x " . $item['quantity'] . " = $" . ($item['price'] * $item['quantity']) . "</li>";
  }
  echo "</ul>";
}

// Remove an item
if (isset($_POST['remove_item_id'])) {
  $productIdToRemove = $_POST['remove_item_id'];
  removeCartItem($productIdToRemove);
  echo "<p>Item removed from cart.</p>";
}

// Update quantity
if (isset($_POST['update_quantity'])) {
    $productId = $_POST['product_id'];
    $newQuantity = $_POST['quantity'];
    updateCartQuantity($productId, $newQuantity);
    echo "<p>Quantity updated for product " . $productId . "</p>";
}


// Optional:  Display remaining items in the cart.  This is useful for a more complete cart system
echo "<br><br><h2>Remaining Items in Cart</h2>";
if (empty($_SESSION['cart'])) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($_SESSION['cart'] as $item) {
    echo "<li>" . $item['name'] . " - $" . $item['price'] . " x " . $item['quantity'] . " = $" . ($item['price'] * $item['quantity']) . "</li>";
  }
  echo "</ul>";
}


?>
