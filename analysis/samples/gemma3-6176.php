

<?php
session_start();

// Initialize the cart if it doesn't exist
if (!isset($_SESSION['cart'])) {
  $_SESSION['cart'] = [];
}

// --- Example Functions to Add/Remove Items ---

// Function to add an item to the cart
function add_to_cart($product_id, $product_name, $price, $quantity = 1) {
  if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
  }

  $item = [
    'id' => $product_id,
    'name' => $product_name,
    'price' => $price,
    'quantity' => $quantity
  ];

  $_SESSION['cart'][] = $item;
  return true; // Indicate success
}

// Function to remove an item from the cart by ID
function remove_from_cart($product_id) {
  if (isset($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $key => $item) {
      if ($item['id'] == $product_id) {
        unset($_SESSION['cart'][$key]);
        // Re-index the array if necessary to avoid key gaps
        $_SESSION['cart'] = array_values($_SESSION['cart']);
        return true;
      }
    }
  }
  return false;
}

// Function to update the quantity of an item in the cart
function update_quantity($product_id, $new_quantity) {
    if (isset($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $key => $item) {
            if ($item['id'] == $product_id) {
                $item['quantity'] = $new_quantity;
                $_SESSION['cart'][$key] = $item;
                $_SESSION['cart'] = array_values($_SESSION['cart']); // Re-index
                return true;
            }
        }
    }
    return false;
}

// --- Example Usage (Demonstration) ---

// Add some items to the cart
add_to_cart(1, 'T-Shirt', 20.00, 2);
add_to_cart(2, 'Jeans', 50.00, 1);
add_to_cart(1, 'T-Shirt', 20.00, 1); // Add another one for testing

// Display the cart contents
echo "<h2>Cart Contents:</h2>";
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
remove_from_cart(2);

// Display the cart contents again
echo "<br>";
echo "<h2>Cart Contents (After Removal):</h2>";
if (empty($_SESSION['cart'])) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($_SESSION['cart'] as $item) {
    echo "<li>" . $item['name'] . " - $" . $item['price'] . " x " . $item['quantity'] . " = $" . ($item['price'] * $item['quantity']) . "</li>";
  }
  echo "</ul>";
}

// Update quantity of a product
update_quantity(1, 3);

echo "<br>";
echo "<h2>Cart Contents (After Update):</h2>";
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
