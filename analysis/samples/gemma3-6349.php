
<!DOCTYPE html>
<html>
<head>
  <title>Shopping Cart</title>
</head>
<body>

  <h1>Shopping Cart</h1>

  <!-- Display cart content - the above PHP code generates this -->

  <script>
    // Example JavaScript function to update the quantity
    function updateQuantity(productId, quantityChange) {
      //  This is a simplified example.  In a real application, you'd send
      //  a request to the server to update the cart.
      //  For simplicity, we'll just update a local variable (not persistent)
      //  This is for demonstration only!

      // In a real application, you would:
      // 1. Make an AJAX request to your PHP server to update the cart.
      // 2. The PHP server would update the session.
      // 3. Then, you would refresh the page (or use JavaScript to update the DOM).

      // Simulated update (DO NOT USE IN PRODUCTION - ONLY FOR DEMO)
      console.log("Updating quantity of product " + productId + " by " + quantityChange);
      // You'd replace this with a server-side update.
    }
  </script>
</body>
</html>


<?php
session_start();

// Initialize the cart if it doesn't exist
if (!isset($_SESSION['cart'])) {
  $_SESSION['cart'] = array();
}

// Function to add an item to the cart
function addItemToCart($productId, $productName, $price, $quantity = 1) {
  if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
  }

  // Check if the product is already in the cart
  if (isset($_SESSION['cart'][$productId])) {
    // If yes, increment the quantity
    $_SESSION['cart'][$productId]['quantity'] += $quantity;
  } else {
    // If not, add the product to the cart with quantity 1
    $_SESSION['cart'][$productId] = array(
      'name' => $productName,
      'price' => $price,
      'quantity' => $quantity
    );
  }
}

// Function to remove an item from the cart
function removeItemFromCart($productId) {
  if (isset($_SESSION['cart'][$productId])) {
    unset($_SESSION['cart'][$productId]);
  }
}

// Function to update the quantity of an item in the cart
function updateQuantity($productId, $newQuantity) {
  if (isset($_SESSION['cart'][$productId])) {
    $_SESSION['cart'][$productId]['quantity'] = $newQuantity;
  }
}

// Function to get the cart items
function getCartItems() {
  return $_SESSION['cart'];
}

// Example Usage (Demonstration)

// Add some items to the cart
addItemToCart(1, 'Laptop', 1200, 1);
addItemToCart(2, 'Mouse', 25, 2);
addItemToCart(1, 'Laptop', 1200, 3); // Adding more of the same item

// Display the cart contents
echo "<h2>Your Shopping Cart</h2>";
if (empty($_SESSION['cart'])) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($_SESSION['cart'] as $id => $item) {
    echo "<li>";
    echo "<strong>Product:</strong> " . $item['name'] . "<br>";
    echo "<strong>Price:</strong> $" . $item['price'] . "<br>";
    echo "<strong>Quantity:</strong> " . $item['quantity'] . "<br>";
    echo "<strong>Total for this item:</strong> $" . $item['price'] * $item['quantity'] . "<br>";
    echo "</li>";
  }
  echo "</ul>";
}

// Example: Remove an item
// removeItemFromCart(2);

// Example: Update the quantity
// updateQuantity(1, 5);

// To view updated cart after changes, uncomment the display section again
// echo "<h2>Your Shopping Cart</h2>";
// if (empty($_SESSION['cart'])) {
//     echo "<p>Your cart is empty.</p>";
// } else {
//    echo "<ul>";
//    foreach ($_SESSION['cart'] as $id => $item) {
//        echo "<li>";
//        echo "<strong>Product:</strong> " . $item['name'] . "<br>";
//        echo "<strong>Price:</strong> $" . $item['price'] . "<br>";
//        echo "<strong>Quantity:</strong> " . $item['quantity'] . "<br>";
//        echo "<strong>Total for this item:</strong> $" . $item['price'] * $item['quantity'] . "<br>";
//        echo "</li>";
//    }
//    echo "</ul>";
// }
?>
