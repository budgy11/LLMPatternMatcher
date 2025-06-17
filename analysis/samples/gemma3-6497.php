
<script>
  function updateQuantity(productId, change) {
    //  This is just a basic example.  In a real application, you'd update
    //  the cart data on the server-side (e.g., via an AJAX request).

    // For demonstration purposes, we'll simulate updating the quantity in the session.
    // THIS IS NOT A PRODUCTION-READY SOLUTION.

    // You would typically use AJAX to send the productId and change to the server
    // and the server would update the session.

    //  This is just a simplified demonstration.

    if (change > 0) {
        // Simulate increasing quantity (server-side update is needed here)
        console.log("Simulating increasing quantity of product " + productId);
    } else if (change < 0) {
        // Simulate decreasing quantity
        console.log("Simulating decreasing quantity of product " + productId);
    }

  }
</script>


<?php
session_start();

// 1. Check if the item is already in the cart
if (isset($_GET['add']) && $_GET['add'] == 'true') {
  $product_id = $_GET['product_id'];
  $quantity = $_GET['quantity'];

  // Validate input (important for security)
  if (is_numeric($quantity) && $quantity > 0) {

    if (isset($_SESSION['cart'][$product_id]) {
      // Item already exists in the cart - increase the quantity
      $_SESSION['cart'][$product_id]['quantity'] += $quantity;
    } else {
      // Item is not in the cart - add it
      $_SESSION['cart'][$product_id] = array(
        'quantity' => $quantity,
        'price'    => $_GET['price'] // Assuming price is sent from the product page
      );
    }
  }
}

// 2. Display the cart contents
echo "<h1>Your Shopping Cart</h1>";

if (empty($_SESSION['cart'])) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($_SESSION['cart'] as $product_id => $details) {
    echo "<li>";
    echo "Product ID: " . $product_id . "<br>";
    echo "Quantity: " . $details['quantity'] . "<br>";
    echo "Price: " . $details['price'] . "<br>";
    echo "Subtotal: " . ($details['quantity'] * $details['price']) . "<br>";
    echo "</li>";
  }
  echo "</ul>";
}

// 3.  (Optional)  Clear the cart (e.g., when the user clicks a "Clear Cart" button)
// if (isset($_POST['clear_cart'])) {
//   unset($_SESSION['cart']);
//   // Redirect the user to the cart page to refresh
//   header("Location: cart.php");
//   exit();
// }


// Example HTML for adding items to the cart (for demonstration)
?>
