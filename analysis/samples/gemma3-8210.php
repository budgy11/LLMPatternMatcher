

<?php
session_start();  // Start the session to store cart data

// Check if 'cart' session variable exists. If not, initialize it.
if (!isset($_SESSION['cart'])) {
  $_SESSION['cart'] = [];
}

// If the user adds an item to the cart:
if (isset($_POST['add_to_cart'])) {
  $product_id = (int)$_POST['product_id']; // Ensure it's an integer
  $quantity = (int)$_POST['quantity'];

  // Find the product
  $product = null;
  foreach ($products as $p) {
    if ($p['id'] == $product_id) {
      $product = $p;
      break;
    }
  }

  if ($product) {
    // Add to cart
    if (isset($_SESSION['cart'][$product_id])) {
      $_SESSION['cart'][$product_id]['quantity'] += $quantity;
    } else {
      $_SESSION['cart'][$product_id] = [
        'id' => $product_id,
        'name' => $product['name'],
        'price' => $product['price'],
        'quantity' => $quantity
      ];
    }
  }
}


//If the user removes an item from cart
if (isset($_POST['remove_item'])) {
    $item_id = (int)$_POST['item_id'];

    if (isset($_SESSION['cart'][$item_id])) {
        unset($_SESSION['cart'][$item_id]);
    }
}


// Display the cart
echo '<h2>Your Shopping Cart</h2>';
if (empty($_SESSION['cart'])) {
  echo '<p>Your cart is empty.</p>';
} else {
  echo '<ul>';
  foreach ($_SESSION['cart'] as $item_id => $cart_item) {
    echo '<li>' . $cart_item['name'] . ' - $' . $cart_item['price'] . ' x ' . $cart_item['quantity'] . ' = $' . ($cart_item['price'] * $cart_item['quantity']) . '</li>';
  }
  echo '</ul>';

  // Calculate total
  $total = 0;
  foreach ($_SESSION['cart'] as $item_id => $cart_item) {
    $total += ($cart_item['price'] * $cart_item['quantity']);
  }
  echo '<p>Total: $' . $total . '</p>';
}
?>
