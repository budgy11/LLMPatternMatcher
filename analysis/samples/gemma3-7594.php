<?php } ?>

</body>
</html>


<?php

// --- Configuration ---
$cart = array(); // Array to store items in the cart
$product_data = array(
  "product1" => array("name" => "Laptop", "price" => 1200),
  "product2" => array("name" => "Mouse", "price" => 25),
  "product3" => array("name" => "Keyboard", "price" => 75),
  "product4" => array("name" => "Monitor", "price" => 300)
);

// --- Functions ---

/**
 * Adds an item to the cart.
 *
 * @param string $product_id The ID of the product to add.
 * @param int $quantity The quantity to add (default is 1).
 */
function addToCart($product_id, $quantity = 1) {
  if (isset($product_data[$product_id])) {
    $product = $product_data[$product_id];
    $cart[] = array(
      "product_id" => $product_id,
      "name" => $product["name"],
      "price" => $product["price"],
      "quantity" => $quantity
    );
    echo "<p>Added " . $quantity . " " . $product["name"] . " to your cart.</p>";
  } else {
    echo "<p>Product with ID '$product_id' not found.</p>";
  }
}

/**
 * Displays the cart contents.
 */
function displayCart() {
  echo "<h2>Your Shopping Cart</h2>";
  if (empty($cart)) {
    echo "<p>Your cart is empty.</p>";
  } else {
    echo "<ul>";
    foreach ($cart as $item) {
      echo "<li>" . $item["name"] . " - $" . $item["price"] . " x " . $item["quantity"] . " = $" . ($item["price"] * $item["quantity"]) . "</li>";
    }
    echo "</ul>";
    echo "<p><strong>Total:</strong> $" . array_sum(array_map(function($item) { return $item['price'] * $item['quantity']; }, $cart)) . "</p>";
  }
}


/**
 * Handles the purchase process.
 * (Simplified for demonstration purposes)
 */
function processPurchase() {
  if (empty($cart)) {
    echo "<p>Your cart is empty.  You cannot purchase anything.</p>";
  } else {
    echo "<h2>Processing Your Order</h2>";
    echo "<p>Thank you for your order!</p>";
    // In a real application, you would:
    // 1.  Validate the order details (e.g., payment information).
    // 2.  Send an email confirmation to the customer.
    // 3.  Update inventory.
    // 4.  Remove items from the cart.

    // Clear the cart after purchase
    $cart = array();
    echo "<p>Your cart has been emptied.</p>";
  }
}


// --- Main execution ---

// Example usage:
if (isset($_GET['add_to_cart'])) {
  $product_id = $_GET['add_to_cart'];
  addToCart($product_id);
}

if (isset($_GET['purchase'])) {
  processPurchase();
}

// Display the cart
displayCart();

?>
