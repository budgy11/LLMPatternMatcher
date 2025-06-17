

<?php
session_start();

// Check if the cart is empty or not
if (!isset($_SESSION['cart'])) {
  $_SESSION['cart'] = array(); // Initialize the cart
}

// Function to add an item to the cart
function add_to_cart($item_id, $item_name, $item_price, $quantity = 1) {
  if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
  }

  // Check if the item already exists in the cart
  if (isset($_SESSION['cart'][$item_id])) {
    // Item already exists, increment the quantity
    $_SESSION['cart'][$item_id]['quantity'] += $quantity;
  } else {
    // Item doesn't exist, add it to the cart
    $_SESSION['cart'][$item_id] = array(
      'name' => $item_name,
      'price' => $item_price,
      'quantity' => $quantity
    );
  }
}

// Example Usage:  You would typically call this from a form submission
// add_to_cart(1, 'T-Shirt', 20, 2); // Add two T-shirts
// add_to_cart(2, 'Jeans', 50, 1);   // Add one pair of jeans


// Function to remove an item from the cart
function remove_from_cart($item_id) {
  if (isset($_SESSION['cart'][$item_id])) {
    unset($_SESSION['cart'][$item_id]);
  }
}

// Function to update the quantity of an item in the cart
function update_cart_quantity($item_id, $quantity) {
  if (isset($_SESSION['cart'][$item_id])) {
    $_SESSION['cart'][$item_id]['quantity'] = $quantity;
  }
}

// Function to display the cart contents
function display_cart() {
  echo "<h2>Shopping Cart</h2>";
  echo "<table border='1'>";
  echo "<tr><th>Item</th><th>Price</th><th>Quantity</th><th>Total</th></tr>";

  $total = 0;
  foreach ($_SESSION['cart'] as $item_id => $item_details) {
    $item_name = $item_details['name'];
    $item_price = $item_details['price'];
    $quantity = $item_details['quantity'];
    $item_total = $item_total = $item_price * $quantity;
    $total += $item_total;

    echo "<tr>";
    echo "<td>" . $item_name . "</td>";
    echo "<td>$" . number_format($item_price, 2) . "</td>";
    echo "<td>" . $quantity . "</td>";
    echo "<td>$" . number_format($item_total, 2) . "</td>";
    echo "</tr>";
  }

  echo "</table><br>";
  echo "<p><strong>Total: $" . number_format($total, 2) . "</strong></p>";
}

//  Example:  If you were processing a form submission, you would call this.
// display_cart();


// Example form submission handling (replace with your actual form processing)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  if (isset($_POST['item_id']) && isset($_POST['quantity'])) {
    $item_id = $_POST['item_id'];
    $quantity = intval($_POST['quantity']); // Convert to integer

    // Add the item to the cart
    add_to_cart($item_id, "Product " . $item_id, 25, $quantity);

    // Display the updated cart
    display_cart();
  }

} else {
  // Initial display of the cart.
  display_cart();
}
?>
