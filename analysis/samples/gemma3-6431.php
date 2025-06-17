

<?php

// Start session
session_start();

// Cart data (in a real application, this would be stored in a database)
$cart = [];

// Function to add an item to the cart
function addToCart($product_id, $product_name, $price, $quantity = 1) {
  // Check if the product is already in the cart
  if (isset($cart[$product_id])) {
    $cart[$product_id]['quantity'] += $quantity;
  } else {
    $cart[$product_id] = [
      'product_id' => $product_id,
      'product_name' => $product_name,
      'price' => $price,
      'quantity' => $quantity
    ];
  }
}

// Function to remove an item from the cart
function removeFromCart($product_id) {
  if (isset($cart[$product_id])) {
    unset($cart[$product_id]);
  }
}

// Function to update quantity of an item in the cart
function updateQuantity($product_id, $new_quantity) {
    if (isset($cart[$product_id])) {
        $cart[$product_id]['quantity'] = $new_quantity;
    }
}

// Function to display the cart
function displayCart() {
  echo "<h2>Your Cart</h2>";

  if (empty($cart)) {
    echo "<p>Your cart is empty.</p>";
    return;
  }

  echo "<ul>";
  foreach ($cart as $product_id => $product) {
    echo "<li>";
    echo "<strong>Product:</strong> " . $product['product_name'] . "<br>";
    echo "<strong>Price:</strong> $" . $product['price'] . "<br>";
    echo "<strong>Quantity:</strong> " . $product['quantity'] . "<br>";
    echo "<form method='post'>";
    echo "<label for='quantity'>" . $product_id . " Quantity:</label>";
    echo "<input type='number' id='quantity' name='quantity" . $product_id . "' value='" . $product['quantity'] . "' min='1'>";
    echo "<input type='submit' value='Update'>";
    echo "</form>";
    echo "</li>";
  }
  echo "</ul>";

  // Calculate total
  $total = 0;
  foreach ($cart as $product_id => $product) {
      $total += $product['price'] * $product['quantity'];
  }

  echo "<p><strong>Total:</strong> $" . $total . "</p>";
}


// --- Example Usage ---

// Add some items to the cart
addToCart(1, "T-Shirt", 20, 2);
addToCart(2, "Jeans", 50, 1);
addToCart(1, "T-Shirt", 20, 3); // Add more of the same item

// Display the cart
displayCart();

// Update the quantity of a product
if (isset($_POST['quantity1'])) {
    updateQuantity(1, $_POST['quantity1']); // Update T-Shirt quantity
}
// Display the cart again after update
displayCart();


// --- Cleanup (Optional - for demonstration only) ---
// To clear the cart for the next session:
// session_destroy();
// session_start();
?>
