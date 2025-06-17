

<?php
session_start();

// Initialize the cart session if it doesn't exist
if (!isset($_SESSION['cart'])) {
  $_SESSION['cart'] = array();
}

// Function to add an item to the cart
function addToCart($product_id, $product_name, $price, $quantity = 1) {
  if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
  }

  // Check if the product is already in the cart
  if (isset($_SESSION['cart'][$product_id])) {
    // Increment quantity if product already exists
    $_SESSION['cart'][$product_id]['quantity'] += $quantity;
  } else {
    // Add the product to the cart
    $_SESSION['cart'][$product_id] = array(
      'name' => $product_name,
      'price' => $price,
      'quantity' => $quantity
    );
  }
}

// Example usage (add some items to the cart)
addToCart(1, 'Laptop', 1200, 1);
addToCart(2, 'Mouse', 25, 2);
addToCart(1, 'Laptop', 1200, 1); // Add another laptop to increase quantity


// Function to remove an item from the cart
function removeCartItem($product_id) {
  if (isset($_SESSION['cart'][$product_id])) {
    unset($_SESSION['cart'][$product_id]);
  }
}

// Example usage (remove an item)
removeCartItem(2);

// Function to update the quantity of an item in the cart
function updateCartItemQuantity($product_id, $new_quantity) {
  if (isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id]['quantity'] = $new_quantity;
  }
}

//Example Usage (Update the quantity of Laptop)
updateCartItemQuantity(1, 3); //Change the quantity of laptop to 3.

// Display the cart contents
echo "<h2>Cart Contents:</h2>";
if (empty($_SESSION['cart'])) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($_SESSION['cart'] as $product_id => $product_info) {
    echo "<li>";
    echo "<strong>" . $product_info['name'] . "</strong> - $" . $product_info['price'] . " x " . $product_info['quantity'] . " = $" . ($product_info['price'] * $product_info['quantity']) . "</li>";
  }
  echo "</ul>";
}

// You can also calculate the total cost of the cart here.
echo "<p><strong>Total Cost:</strong> $" . number_format(calculateTotal(), 2) . "</p>";

//Helper function to calculate total cost
function calculateTotal() {
    $total = 0;
    if (isset($_SESSION['cart'])) {
        foreach($_SESSION['cart'] as $product_id => $product_info){
            $total += ($product_info['price'] * $product_info['quantity']);
        }
    }
    return $total;
}
?>
