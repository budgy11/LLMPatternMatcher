

<?php
session_start();

// --- Example Cart Data ---
$cart = array();

// Functions to manipulate the cart
function add_to_cart($product_id, $product_name, $price, $quantity = 1) {
  if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
  }

  $_SESSION['cart'][] = array(
    'id' => $product_id,
    'name' => $product_name,
    'price' => $price,
    'quantity' => $quantity
  );
}

function remove_from_cart($product_id) {
  if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $key => $item) {
      if ($item['id'] == $product_id) {
        unset($_SESSION['cart'][$key]);
        // Optionally, re-index the array if needed
        // reset($_SESSION['cart']);
        return true;
      }
    }
    return false; // Item not found
  }
  return false; // Cart is empty
}

function update_quantity($product_id, $new_quantity) {
  if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $key => $item) {
      if ($item['id'] == $product_id) {
        $_SESSION['cart'][$key]['quantity'] = $new_quantity;
        return true;
      }
    }
    return false; // Item not found
  }
  return false; // Cart is empty
}

function get_cart_contents() {
  if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
    return $_SESSION['cart'];
  }
  return array(); // Return an empty array if the cart is empty
}

function calculate_total() {
    $total = 0;
    $cart_contents = get_cart_contents();
    foreach ($cart_contents as $item) {
        $total += $item['price'] * $item['quantity'];
    }
    return $total;
}

// --- Example Usage ---

// Add a product to the cart
add_to_cart(1, "Awesome T-Shirt", 25.00, 2);
add_to_cart(2, "Cool Mug", 10.00, 1);

// Display the cart contents
echo "<h2>Your Shopping Cart</h2>";
echo "<ul>";
$cart_contents = get_cart_contents();
if (!empty($cart_contents)) {
    foreach ($cart_contents as $item) {
        echo "<li>" . $item['name'] . " - $" . $item['price'] . " x " . $item['quantity'] . " = $" . ($item['price'] * $item['quantity']) . "</li>";
    }
} else {
    echo "<li>Your cart is empty.</li>";
}
echo "</ul>";

echo "<p><strong>Total:</strong> $" . calculate_total() . "</p>";

// Remove a product
if (remove_from_cart(1)) {
  echo "<p>Awesome T-Shirt removed from cart.</p>";
} else {
  echo "<p>Unable to remove Awesome T-Shirt.</p>";
}


//Update quantity
if (update_quantity(2, 3)) {
    echo "<p>Mug quantity updated to 3</p>";
} else {
    echo "<p>Unable to update Mug quantity.</p>";
}

//Display updated cart
echo "<h2>Your Updated Shopping Cart</h2>";
echo "<ul>";
$cart_contents = get_cart_contents();
if (!empty($cart_contents)) {
    foreach ($cart_contents as $item) {
        echo "<li>" . $item['name'] . " - $" . $item['price'] . " x " . $item['quantity'] . " = $" . ($item['price'] * $item['quantity']) . "</li>";
    }
} else {
    echo "<li>Your cart is empty.</li>";
}
echo "</ul>";
?>
