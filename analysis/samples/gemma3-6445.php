

<?php

// Session setup (if not already initialized)
session_start();

// --- Cart Data (This would ideally be stored in a database for real-world scenarios) ---
$cart = array();

// Function to add an item to the cart
function addToCart($product_id, $product_name, $price, $quantity = 1) {
  global $cart;

  if (!isset($cart[$product_id])) {
    $cart[$product_id] = array(
      'name' => $product_name,
      'price' => $price,
      'quantity' => $quantity,
      'total' => $price * $quantity
    );
  } else {
    $cart[$product_id]['quantity'] += $quantity;
    $cart[$product_id]['total'] = $cart[$product_id]['price'] * $cart[$product_id]['quantity'];
  }
}

// Function to update the quantity of an item in the cart
function updateCartQuantity($product_id, $quantity) {
    global $cart;

    if (isset($cart[$product_id])) {
        $cart[$product_id]['quantity'] = $quantity;
        $cart[$product_id]['total'] = $cart[$product_id]['price'] * $cart[$product_id]['quantity'];
    } else {
        // Item not in cart, handle error or add it with default quantity
        // You might want to log this or display an error message.
        echo "Error: Item ID " . $product_id . " not found in cart.";
    }
}


// Function to remove an item from the cart
function removeFromCart($product_id) {
  global $cart;

  if (isset($cart[$product_id])) {
    unset($cart[$product_id]);
  } else {
    echo "Error: Item ID " . $product_id . " not found in cart.";
  }
}

// Function to get the cart contents
function getCartContents() {
  return $cart;
}

// Function to clear the cart
function clearCart() {
  $cart = array();
}



// --- Example Usage (Simulating User Interactions) ---

// 1. Add some items to the cart
addToCart(1, "T-Shirt", 20, 2);
addToCart(2, "Jeans", 50, 1);
addToCart(1, "T-Shirt", 20, 3);  // Adding more of the same item

// 2. Display the cart contents
$cart_contents = getCartContents();
echo "<h2>Your Cart</h2>";
if (empty($cart_contents)) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($cart_contents as $product_id => $product_data) {
    echo "<li>" . $product_data['name'] . " - $" . $product_data['price'] . " x " . $product_data['quantity'] . " = $" . $product_data['total'] . "</li>";
  }
  echo "</ul>";
}


// 3.  Update Quantity
updateCartQuantity(1, 4); // Update quantity of T-Shirt to 4

// Display updated cart
$cart_contents = getCartContents();
echo "<h2>Your Cart (Updated)</h2>";
if (empty($cart_contents)) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($cart_contents as $product_id => $product_data) {
    echo "<li>" . $product_data['name'] . " - $" . $product_data['price'] . " x " . $product_data['quantity'] . " = $" . $product_data['total'] . "</li>";
  }
  echo "</ul>";
}

// 4. Remove an Item
removeFromCart(2);

// 5. Clear the Cart
//clearCart(); // Uncomment to clear the cart
?>
