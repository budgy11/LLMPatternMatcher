

<?php
session_start();

// Example:  Let's say we're selling a product called "Awesome T-Shirt"
// and it costs $20.

// 1. Add to Cart Function
function addToCart($productName, $price) {
  // Check if the cart already exists in the session
  if (isset($_SESSION['cart'])) {
    // If it exists, add the product to the array
    $_SESSION['cart'][] = ['name' => $productName, 'price' => $price];
  } else {
    // If it doesn't exist, create an empty array
    $_SESSION['cart'] = [];
    // Add the product to the array
    $_SESSION['cart'][] = ['name' => $productName, 'price' => $price];
  }
}

// 2. View Cart Function
function viewCart() {
  if (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0) {
    echo "<h2>Your Shopping Cart</h2>";
    echo "<ul>";
    foreach ($_SESSION['cart'] as $item) {
      echo "<li>" . $item['name'] . " - $" . $item['price'] . "</li>";
    }
    echo "</ul>";
    echo "<p>Total: $" . calculateTotal() . "</p>"; // Calculates the total
  } else {
    echo "<p>Your cart is empty.</p>";
  }
}

// 3. Calculate Total Function
function calculateTotal() {
  $total = 0;
  if (isset($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $item) {
      $total += $item['price'];
    }
  }
  return $total;
}

// --------------------  Example Usage  --------------------

//  Add an item to the cart
addToCart("Awesome T-Shirt", 20);
addToCart("Cool Hat", 15);
addToCart("Super Socks", 8);

// View the cart
viewCart();

// Let's remove a single item from the cart.
// In a real application, you'd likely have a function for this.
// For this example, we'll manually remove the "Super Socks"
// (This is a simplified demonstration - usually you'd have an ID or similar)

// Remove Super Socks (simplified - a proper system would use an ID)
// Note:  This is just to demonstrate - in a real implementation, you would need
// a way to identify *which* product to remove.
if (isset($_SESSION['cart']) && isset($_SESSION['cart'][0]['name']) && $_SESSION['cart'][0]['name'] === 'Super Socks') {
    unset($_SESSION['cart'][0]);
    //If you remove the last item, you may need to re-index the array.
    //This is a simplification.
    //Reset the array to ensure correct indexing after deletion.
    $_SESSION['cart'] = array_values($_SESSION['cart']);

}

// View the cart after removing
viewCart();

?>
