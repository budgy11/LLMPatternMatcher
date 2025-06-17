

<?php
session_start();

// --- Session Variables ---
// Define keys for your session variables
$product_id = $_GET['product_id'];
$product_name = $_GET['product_name'];
$quantity = isset($_GET['quantity']) ? (int)$_GET['quantity'] : 1; // Default to 1 if not provided
$total_price = (float)$_GET['price']; // Price for the product
$cart_items = [];

// --- Function to Add Item to Cart ---
function add_to_cart($product_id, $product_name, $quantity, $total_price) {
  global $cart_items;

  // Check if the product is already in the cart
  foreach ($cart_items as $key => $item) {
    if ($item['product_id'] == $product_id) {
      // Update the quantity
      $cart_items[$key]['quantity'] += $quantity;
      $cart_items[$key]['total_price'] = $cart_items[$key]['quantity'] * $cart_items[$key]['price']; // recalculate
      return true;
    }
  }

  // If product not in cart, add it
  $cart_items[] = [
    'product_id' => $product_id,
    'product_name' => $product_name,
    'quantity' => $quantity,
    'price' => $total_price,
    'total_price' => $quantity * $total_price  // Calculate total price immediately
  ];
  return true;
}

// --- Handle Adding to Cart (Example from POST request) ---
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  // You might receive this data from a form submission
  if (isset($_POST['product_id']) && isset($_POST['quantity'])) {
    $product_id = $_POST['product_id'];
    $quantity = (int)$_POST['quantity'];
    $total_price = (float)$_POST['price'];

    add_to_cart($product_id, $product_name, $quantity, $total_price);

    //  You'd likely redirect after adding the item, for example:
    // header("Location: cart.php"); // Redirect to your cart page
    // exit;

  }
}

// --- Display Cart Contents (for demonstration) ---
echo "<h2>Your Cart</h2>";

if (empty($cart_items)) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($cart_items as $index => $item) {
    echo "<li>" . $item['product_name'] . " - Quantity: " . $item['quantity'] . " - Price: $" . $item['price'] . " - Total: $" . $item['total_price'] . "</li>";
  }
  echo "</ul>";
}

?>
