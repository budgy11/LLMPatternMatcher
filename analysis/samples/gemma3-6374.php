

<?php
session_start();

// Session variables for the cart
$cart = array();

// Function to add an item to the cart
function addItemToCart($item_id, $item_name, $price, $quantity = 1) {
  if (empty($_SESSION['cart'])) {
    $cart = array();
  }

  $cart[] = array(
    'id' => $item_id,
    'name' => $item_name,
    'price' => $price,
    'quantity' => $quantity
  );

  // You might want to store the cart in a session for persistence
  $_SESSION['cart'] = $cart;
}

// Function to update the quantity of an item in the cart
function updateCartItemQuantity($item_id, $quantity) {
  if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $key => $item) {
      if ($item['id'] == $item_id) {
        $_SESSION['cart'][$key]['quantity'] = $quantity;
        return true;
      }
    }
    return false; // Item not found
  }
  return false; // Cart is empty
}

// Function to remove an item from the cart
function removeItemFromCart($item_id) {
  if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $key => $item) {
      if ($item['id'] == $item_id) {
        unset($_SESSION['cart'][$key]);
        // Re-index the array to avoid key issues after deleting
        $_SESSION['cart'] = array_values($_SESSION['cart']);
        return true;
      }
    }
    return false; // Item not found
  }
  return false; // Cart is empty
}

// Function to get the cart contents
function getCartContents() {
  return $_SESSION['cart'];
}


// ------------------  Example Usage  ------------------

// 1. Add an item to the cart
addItemToCart(1, "T-Shirt", 20.00, 2);
addItemToCart(2, "Jeans", 50.00);

// 2. Update the quantity of an item
updateCartItemQuantity(1, 3); // Increase the quantity of the T-Shirt to 3

// 3. Remove an item from the cart
removeItemFromCart(2); // Remove the Jeans

// 4. Get and display the cart contents
$cart_contents = getCartContents();

echo "<h2>Cart Contents:</h2>";
if (empty($cart_contents)) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($cart_contents as $item) {
    echo "<li>" . $item['name'] . " - $" . $item['price'] . " x " . $item['quantity'] . " = $" . ($item['price'] * $item['quantity']) . "</li>";
  }
  echo "</ul>";
}


//  Example of clearing the cart (e.g., after checkout)
//  session_destroy();
?>
