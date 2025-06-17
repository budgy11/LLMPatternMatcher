

<?php
session_start();

// Function to add an item to the cart
function addToCart($product_id, $product_name, $price, $quantity = 1) {
  if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
  }

  // Check if the product already exists in the cart
  if (isset($_SESSION['cart'][$product_id])) {
    // If it exists, increment the quantity
    $_SESSION['cart'][$product_id]['quantity'] += $quantity;
  } else {
    // If it doesn't exist, add the product to the cart
    $_SESSION['cart'][$product_id] = [
      'name' => $product_name,
      'price' => $price,
      'quantity' => $quantity
    ];
  }
}

// Function to update the quantity of an item in the cart
function updateQuantity($product_id, $new_quantity) {
  if (isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id]['quantity'] = $new_quantity;
  }
}

// Function to remove an item from the cart
function removeCartItem($product_id) {
  if (isset($_SESSION['cart'][$product_id])) {
    unset($_SESSION['cart'][$product_id]);
  }
}

// Function to display the cart contents
function displayCart() {
  if (empty($_SESSION['cart'])) {
    echo "<p>Your cart is empty.</p>";
    return;
  }

  echo "<h2>Cart Items</h2>";
  echo "<ul>";
  foreach ($_SESSION['cart'] as $product_id => $item) {
    echo "<li>";
    echo "<strong>" . $item['name'] . "</strong> - $" . $item['price'] . " x " . $item['quantity'];
    echo "<br>";
    echo "<form method='post'>";
    echo "<label for='quantity_" . $product_id . "'>Quantity:</label>";
    echo "<input type='number' id='quantity_" . $product_id . "' name='quantity_" . $product_id . "' value='" . $item['quantity'] . "' min='1'>";
    echo "<input type='submit' name='update_quantity_" . $product_id . "' value='Update'>";
    echo "<br>";
    echo "<input type='submit' name='remove_" . $product_id . "' value='Remove Cart Item' formnovalidate>";
    echo "</li>";
  }
  echo "</ul>";

  // Calculate the total price
  $total = 0;
  foreach ($_SESSION['cart'] as $product_id => $item) {
    $total += $item['price'] * $item['quantity'];
  }

  echo "<p><strong>Total: $" . $total . "</strong></p>";
}

// --- Example Usage (Simulating User Interactions) ---

// 1. Adding items to the cart
addToCart(1, "T-Shirt", 20, 2);
addToCart(2, "Jeans", 50, 1);
addToCart(1, "T-Shirt", 20, 3); // Adding more of the same item


// 2. Updating the quantity of an item
updateQuantity(1, 5); // Change quantity of T-Shirt to 5

// 3. Removing an item
removeCartItem(2);

// 4. Displaying the cart
displayCart();


//  --- End of Example Usage ---

?>
