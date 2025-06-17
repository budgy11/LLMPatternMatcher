

<?php
session_start();

// Database connection details (replace with your actual credentials)
$db_host = "localhost";
$db_user = "your_username";
$db_password = "your_password";
$db_name = "your_database";

// Initialize the cart (empty array if none exists)
if (!isset($_SESSION['cart'])) {
  $_SESSION['cart'] = [];
}

// --- Helper Functions ---

// Function to add an item to the cart
function addToCart($product_id, $quantity, $product_name, $product_price) {
  global $_SESSION['cart'];

  if (empty($quantity)) {
    $quantity = 1; // Default to 1 if quantity is not provided
  }

  $item = array(
    'id' => $product_id,
    'name' => $product_name,
    'price' => $product_price,
    'quantity' => $quantity
  );

  // Check if the item is already in the cart
  foreach ($_SESSION['cart'] as $key => $cart_item) {
    if ($cart_item['id'] == $product_id) {
      // Update the quantity
      $_SESSION['cart'][$key]['quantity'] += $quantity;
      return;
    }
  }

  // Add the item to the cart
  $_SESSION['cart'][] = $item;
  return;
}

// Function to remove an item from the cart
function removeItemFromCart($product_id) {
    global $_SESSION['cart'];

    foreach ($_SESSION['cart'] as $key => $cart_item) {
        if ($cart_item['id'] == $product_id) {
            unset($_SESSION['cart'][$key]);
            // Re-index the array to avoid gaps
            $_SESSION['cart'] = array_values($_SESSION['cart']);
            return;
        }
    }
}

// Function to update the quantity of an item in the cart
function updateQuantity($product_id, $quantity) {
    global $_SESSION['cart'];

    foreach ($_SESSION['cart'] as $key => $cart_item) {
        if ($cart_item['id'] == $product_id) {
            if ($quantity > 0) {
                $_SESSION['cart'][$key]['quantity'] = $quantity;
            } else {
                // Remove the item if the quantity is set to 0
                removeItemFromCart($product_id);
                return;
            }
            return;
        }
    }
}

// Function to display the cart
function displayCart() {
  echo "<h2>Shopping Cart</h2>";

  if (empty($_SESSION['cart'])) {
    echo "<p>Your cart is empty.</p>";
    return;
  }

  echo "<ul>";
  $total = 0;
  foreach ($_SESSION['cart'] as $item) {
    echo "<li>";
    echo "<strong>" . $item['name'] . "</strong> - $" . $item['price'] . "</li>";
    $total += $item['price'] * $item['quantity'];
  }
  echo "</ul>";
  echo "<p><strong>Total: $" . $total . "</strong></p>";
}


// ---  Product Data (Simulated - Replace with your database retrieval) ---
$products = [
  1 => ['id' => 1, 'name' => 'Laptop', 'price' => 1200],
  2 => ['id' => 2, 'name' => 'Mouse', 'price' => 25],
  3 => ['id' => 3, 'name' => 'Keyboard', 'price' => 75],
  4 => ['id' => 4, 'name' => 'Monitor', 'price' => 300]
];


// ---  Handling Add to Cart Request ---
if (isset($_POST['add_to_cart'])) {
  $product_id = $_POST['product_id'];
  $quantity = $_POST['quantity'];
  $product_name = $products[$product_id]['name'];
  $product_price = $products[$product_id]['price'];

  addToCart($product_id, $quantity, $product_name, $product_price);
  // Redirect to the cart page (for better user experience)
  header("Location: cart.php");
  exit();
}

// --- Handling Remove from Cart Request ---
if (isset($_POST['remove_from_cart'])) {
  $product_id = $_POST['product_id'];
  removeItemFromCart($product_id);
  header("Location: cart.php");
  exit();
}

// --- Handling Update Quantity Request ---
if (isset($_POST['update_quantity'])) {
  $product_id = $_POST['product_id'];
  $quantity = $_POST['quantity'];
  updateQuantity($product_id, $quantity);
  header("Location: cart.php");
  exit();
}

// --- Display the Cart Page (cart.php) ---
?>
