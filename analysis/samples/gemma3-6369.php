

<?php

session_start();

// --- Cart Session Variables ---
$cart = []; // Array to store items in the cart
$cart_id = 'cart_' . md5(uniqid(rand(), true)); // Unique ID for the session

// --- Helper Functions ---
function add_to_cart($product_id, $product_name, $price, $quantity = 1) {
  global $cart, $cart_id;

  $item = [
    'id' => $product_id,
    'name' => $product_name,
    'price' => $price,
    'quantity' => $quantity
  ];

  // Check if the item is already in the cart
  foreach ($cart as &$item_in_cart) {
    if ($item_in_cart['id'] === $item['id']) {
      $item_in_cart['quantity'] += $item['quantity'];
      return;
    }
  }

  // If not in the cart, add it
  $cart[] = $item;
}

function update_cart_quantity($product_id, $quantity) {
    global $cart;
    foreach ($cart as &$item) {
        if ($item['id'] === $product_id) {
            $item['quantity'] = $quantity;
            return;
        }
    }
}

function remove_from_cart($product_id) {
    global $cart;
    $new_cart = [];
    foreach ($cart as $item) {
        if ($item['id'] !== $product_id) {
            $new_cart[] = $item;
        }
    }
    $cart = $new_cart;
}

function get_cart_total($cart) {
    $total = 0;
    foreach ($cart as $item) {
        $total += $item['price'] * $item['quantity'];
    }
    return $total;
}

// --- Example Usage (Simulate adding items to the cart) ---

// Add a product to the cart
add_to_cart(1, "Laptop", 1200, 1);
add_to_cart(2, "Mouse", 25, 2);


// Update quantity
update_cart_quantity(2, 5);

// Remove item
//remove_from_cart(1);

// --- Displaying the Cart ---
echo "<h2>Your Shopping Cart</h2>";

if (empty($cart)) {
    echo "<p>Your cart is empty.</p>";
} else {
    echo "<ul>";
    foreach ($cart as $item) {
        echo "<li>" . $item['name'] . " - $" . $item['price'] . " x " . $item['quantity'] . " = $" . $item['price'] * $item['quantity'] . "</li>";
    }
    echo "</ul>";

    echo "<p><strong>Total: $" . get_cart_total($cart) . "</strong></p>";
}

?>
