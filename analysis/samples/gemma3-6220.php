

<?php
session_start();

// Array to hold cart items (you'd typically store this in a database)
$cart = array();

// Function to add an item to the cart
function addToCart($product_id, $product_name, $price, $quantity = 1) {
  global $cart;

  if (isset($cart[$product_id])) {
    // Item already in cart, increase quantity
    $cart[$product_id]['quantity'] += $quantity;
  } else {
    // Item not in cart, add it
    $cart[$product_id] = array(
      'name' => $product_name,
      'price' => $price,
      'quantity' => $quantity
    );
  }
}

// Function to get the cart items
function getCart() {
  return $cart;
}

// Function to update the quantity of an item in the cart
function updateCartQuantity($product_id, $quantity) {
    global $cart;

    if (isset($cart[$product_id])) {
        $cart[$product_id]['quantity'] = $quantity;
    } else {
        // Item not in cart, you might want to return an error or do nothing
        // For this example, we'll just return false
        return false;
    }
    return true;
}

// Function to remove an item from the cart
function removeCartItem($product_id) {
    global $cart;

    if (isset($cart[$product_id])) {
        unset($cart[$product_id]);
    }
}


// Sample Usage - Add items to the cart (example)
addToCart(1, "Laptop", 1200, 1);
addToCart(2, "Mouse", 25, 2);
addToCart(1, "Laptop", 1200, 1); // Add another Laptop (duplicates are handled)


// Get the current cart
$current_cart = getCart();

// Display the cart contents
echo "<h2>Your Shopping Cart</h2>";
if (empty($current_cart)) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($current_cart as $product_id => $item) {
    echo "<li>" . $item['name'] . " - $" . $item['price'] . " x " . $item['quantity'] . " = $" . ($item['price'] * $item['quantity']) . "</li>";
  }
  echo "</ul>";
}


// Example of updating the quantity
updateCartQuantity(2, 3);
echo "<br>";

//Display the updated cart
echo "<h2>Your Shopping Cart (Updated)</h2>";
if (empty($current_cart)) {
    echo "<p>Your cart is empty.</p>";
} else {
    echo "<ul>";
    foreach ($current_cart as $product_id => $item) {
        echo "<li>" . $item['name'] . " - $" . $item['price'] . " x " . $item['quantity'] . " = $" . ($item['price'] * $item['quantity']) . "</li>";
    }
    echo "</ul>";
}

// Example of removing an item
removeCartItem(2);
echo "<br>";
echo "<h2>Your Shopping Cart (After Removing Item)</h2>";
if (empty($current_cart)) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($current_cart as $product_id => $item) {
    echo "<li>" . $item['name'] . " - $" . $item['price'] . " x " . $item['quantity'] . " = $" . ($item['price'] * $item['quantity']) . "</li>";
  }
  echo "</ul>";
}


?>
