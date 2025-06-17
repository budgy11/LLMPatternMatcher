    <input type="submit" value="Complete Purchase">
</form>

</body>
</html>


<?php
session_start(); // Start the session (required for storing the cart)

// Initialize the cart if it doesn't exist
if (!isset($_SESSION['cart'])) {
  $_SESSION['cart'] = array();
}

// Function to add an item to the cart
function add_to_cart($product_id, $product_name, $price, $quantity = 1) {
  global $_SESSION['cart'];

  if (isset($_SESSION['cart'][$product_id])) {
    // Item already in cart - increment quantity
    $_SESSION['cart'][$product_id]['quantity'] += $quantity;
  } else {
    // Item not in cart - add a new entry
    $_SESSION['cart'][$product_id] = array(
      'name' => $product_name,
      'price' => $price,
      'quantity' => $quantity
    );
  }
}


// Function to remove an item from the cart
function remove_from_cart($product_id) {
  global $_SESSION['cart'];

  if (isset($_SESSION['cart'][$product_id])) {
    unset($_SESSION['cart'][$product_id]);
  }
}

// Function to calculate the total cost of the cart
function calculate_total() {
  global $_SESSION['cart'];
  $total = 0;
  foreach ($_SESSION['cart'] as $item) {
    $total = $total + ($item['price'] * $item['quantity']);
  }
  return $total;
}

// --- Handle Purchase Request ---
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  if (isset($_POST['submit_order'])) {
    // 1. Validate the data (In a real application, do thorough validation!)
    //    Example: Check if product IDs are valid, quantity is greater than 0, etc.

    // 2. Process the order
    $cart = $_SESSION['cart'];

    // Output Order Details (for demonstration)
    echo "<h2>Order Summary:</h2>";
    echo "<ul>";
    foreach ($cart as $product_id => $item) {
      echo "<li>" . $item['name'] . " - Quantity: " . $item['quantity'] . " - Price: $" . $item['price'] . "</li>";
    }
    echo "</ul>";

    $total = calculate_total();
    echo "<p>Total: $" . $total . "</p>";

    // In a real application, you would:
    // - Send an email confirmation to the customer
    // - Update inventory
    // - Record the order in a database
    echo "<p>Order placed successfully! (Simulated)</p>";

    // Clear the cart after successful order
    $_SESSION['cart'] = array();
  }
}
?>
