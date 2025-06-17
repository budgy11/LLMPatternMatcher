
<br>

<a href="checkout.php">Checkout</a>

</body>
</html>


<?php
session_start();

// Define cart data
$cart = [];

// --- Helper Functions ---

// Function to add an item to the cart
function addToCart($item_id, $item_name, $price, $quantity = 1) {
  global $cart;

  if (isset($cart[$item_id])) {
    $cart[$item_id]['quantity'] += $quantity;
  } else {
    $cart[$item_id] = [
      'name' => $item_name,
      'price' => $price,
      'quantity' => $quantity
    ];
  }
}

// Function to update the quantity of an item in the cart
function updateQuantity($item_id, $quantity) {
  global $cart;

  if (isset($cart[$item_id])) {
    $cart[$item_id]['quantity'] = $quantity;
  } else {
    // Item not found - handle appropriately (e.g., display error)
    echo "Error: Item with ID $item_id not found in cart.";
  }
}

// Function to remove an item from the cart
function removeFromCart($item_id) {
  global $cart;

  if (isset($cart[$item_id])) {
    unset($cart[$item_id]);
  } else {
    // Item not found - handle appropriately (e.g., display error)
    echo "Error: Item with ID $item_id not found in cart.";
  }
}

// Function to calculate the total cart value
function calculateTotal() {
  global $cart;
  $total = 0;
  foreach ($cart as $item) {
    $total += $item['price'] * $item['quantity'];
  }
  return $total;
}

// --- Cart Actions (handled by form submissions) ---

if (isset($_POST['action']) && $_POST['action'] == 'add_to_cart') {
  $item_id = $_POST['item_id'];
  $item_name = $_POST['item_name'];
  $price = $_POST['price'];
  $quantity = $_POST['quantity'];

  addToCart($item_id, $item_name, $price, $quantity);
  // Optionally, redirect to the cart page
  header("Location: cart.php"); // Redirects to the cart.php page
  exit();
}

if (isset($_POST['action']) && $_POST['action'] == 'update_quantity') {
  $item_id = $_POST['item_id'];
  $new_quantity = $_POST['quantity'];
  updateQuantity($item_id, $new_quantity);
  header("Location: cart.php");
  exit();
}

if (isset($_POST['action']) && $_POST['action'] == 'remove_from_cart') {
  $item_id = $_POST['item_id'];
  removeFromCart($item_id);
  header("Location: cart.php");
  exit();
}

// --- Display Cart Content (cart.php) ---

// Example cart.php content
?>
