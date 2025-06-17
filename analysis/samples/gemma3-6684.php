

<?php

session_start();

// --- Cart Session Variables ---

// Define keys for cart items
$cartKeys = ['item_id', 'item_name', 'quantity', 'price'];

// Function to add an item to the cart
function add_to_cart($item_id, $item_name, $quantity, $price) {
  global $cartKeys;

  // Check if cart exists.  If not, initialize it.
  if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
  }

  // Add the item to the cart
  $_SESSION['cart'][] = [
    'item_id' => $item_id,
    'item_name' => $item_name,
    'quantity' => $quantity,
    'price' => $price
  ];

  // You might want to update quantity if the item already exists in the cart
  update_cart_item($item_id, $quantity);

}


// Function to update quantity of an item in the cart
function update_cart_item($item_id, $quantity) {
    if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as &$item) { // Use &$item for in-place modification
            if ($item['item_id'] == $item_id) {
                $item['quantity'] = $quantity;
                break; // Exit the loop once the item is updated
            }
        }
    }
}


// Function to remove an item from the cart
function remove_from_cart($item_id) {
  if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $key => $item) {
      if ($item['item_id'] == $item_id) {
        unset($_SESSION['cart'][$key]);
        // Optional:  If you want to re-index the array after removing
        // $_SESSION['cart'] = array_values($_SESSION['cart']);
        return true; // Indicate successful removal
      }
    }
    return false; // Indicate item not found
  }
  return false; // Cart doesn't exist
}


// Function to get the cart total
function get_cart_total() {
  if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
    $total = 0;
    foreach ($_SESSION['cart'] as $item) {
      $total += $item['quantity'] * $item['price'];
    }
    return $total;
  }
  return 0;
}

// Function to display the cart contents
function display_cart() {
  if (empty($_SESSION['cart'])) {
    echo "<p>Your cart is empty.</p>";
    return;
  }

  echo "<h2>Your Cart</h2>";
  echo "<table border='1'>";
  echo "<tr><th>Item Name</th><th>Quantity</th><th>Price</th><th>Total</th></tr>";

  $cart_total = get_cart_total();

  foreach ($_SESSION['cart'] as $item) {
    $item_total = $item['quantity'] * $item['price'];
    echo "<tr>";
    echo "<td>" . $item['item_name'] . "</td>";
    echo "<td>" . $item['quantity'] . "</td>";
    echo "<td>" . $item['price'] . "</td>";
    echo "<td>" . $item_total . "</td>";
    echo "</tr>";
  }

  echo "</table>";
  echo "<p><strong>Total: $" . $cart_total . "</strong></p>";
}

// --- Example Usage (Demonstration) ---

// Add some items to the cart
add_to_cart(1, 'Laptop', 1, 1200);
add_to_cart(2, 'Mouse', 1, 25);
add_to_cart(1, 'Laptop', 2, 1200); // Add another laptop

// Display the cart
display_cart();

// Remove an item
remove_from_cart(2);

// Display the cart again
display_cart();

?>
