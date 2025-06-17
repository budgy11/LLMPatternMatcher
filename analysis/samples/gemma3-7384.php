
</body>
</html>


<?php

// This is a very basic purchase functionality example.
// For a production environment, you'd need to:
// - Secure input and data
// - Implement robust error handling
// - Use a database for persistent storage
// - Integrate with payment gateways (e.g., Stripe, PayPal)
// - Add more features like order tracking, admin panel, etc.


// Simulating a product catalog (replace with a real database or API)
$products = [
    1 => ['id' => 1, 'name' => 'T-Shirt', 'price' => 20],
    2 => ['id' => 2, 'name' => 'Mug', 'price' => 10],
    3 => ['id' => 3, 'name' => 'Hat', 'price' => 15],
];

// Initialize order details
$order_id = 0;
$cart = [];
$total_amount = 0;


// Function to add a product to the cart
function add_to_cart($product_id, $quantity = 1) {
  global $cart, $total_amount;

  if (isset($products[$product_id])) {
    $product = $products[$product_id];

    if (isset($cart[$product_id])) {
      $cart[$product_id]['quantity'] += $quantity;
    } else {
      $cart[$product_id] = ['quantity' => $quantity, 'price' => $product['price']];
    }

    $total_amount += $product['price'] * $quantity;
  } else {
    echo "<p>Product ID: " . $product_id . " not found.</p>";
  }
}

// Function to display the cart
function display_cart() {
  global $cart;

  echo "<h2>Your Cart</h2>";
  if (empty($cart)) {
    echo "<p>Your cart is empty.</p>";
    return;
  }

  echo "<ul>";
  foreach ($cart as $item_id => $item) {
    echo "<li>" . $products[$item_id]['name'] . " - Quantity: " . $item['quantity'] . " - Price: $" . $item['price'] . "</li>";
  }
  echo "</ul>";

  echo "<p><strong>Total: $" . number_format($total_amount, 2) . "</strong></p>";
}


// Handle form submission to add to cart
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Validate the product_id
  $product_id = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0; //Sanitize and convert to integer

  if ($product_id > 0) {
    add_to_cart($product_id);
  } else {
    echo "<p>Invalid product ID.</p>";
  }
}


// Display the cart
display_cart();

?>
