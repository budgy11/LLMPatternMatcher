

<?php
session_start();

// This is a basic example and should be expanded for a real-world e-commerce application.

// --- Session Variables ---
// These are just example keys.  Use your own, and choose appropriate data types.
$_SESSION['cart'] = array();  // Empty cart array
$_SESSION['cart_total'] = 0;
$_SESSION['user_id'] = ''; //  For user authentication (optional)


// --- Function to Add Items to Cart ---
function add_to_cart($product_id, $product_name, $price, $quantity = 1) {
  global $session;  // Access the session variables

  if (!is_array($session['cart'])) {
    $session['cart'] = array(); // Initialize the cart
  }

  $item = array(
    'id' => $product_id,
    'name' => $product_name,
    'price' => $price,
    'quantity' => $quantity
  );

  $session['cart'][] = $item;  // Add to the cart array
  $session['cart_total'] += $price * $quantity; // Update total
}


// --- Example Usage (Simulate Product Data) ---
// Replace this with your actual product data source (database, API, etc.)

$products = array(
  1 => array('id' => 1, 'name' => 'Laptop', 'price' => 1200),
  2 => array('id' => 2, 'name' => 'Mouse', 'price' => 25),
  3 => array('id' => 3, 'name' => 'Keyboard', 'price' => 75),
);



// --- Example Actions (Simulated Buttons/Links) ---

// **1. Add Laptop to Cart**
if (isset($_POST['add_laptop'])) {
  add_to_cart(1, 'Laptop', $products[1]['price']);
}


// **2. Add Mouse to Cart**
if (isset($_POST['add_mouse'])) {
  add_to_cart(2, 'Mouse', $products[2]['price']);
}

// **3. Add Keyboard to Cart**
if (isset($_POST['add_keyboard'])) {
  add_to_cart(3, 'Keyboard', $products[3]['price']);
}

// **4. Simulate User Removing Item From Cart (Example)**
if (isset($_POST['remove_keyboard'])) {
  // In a real application, you'd need to identify the item to remove based on
  // the product ID and remove it from the cart.  This is a simplified example.
  // This particular example is just for demonstration.
  // In a production environment, this would likely involve a database query.
  if (isset($_GET['product_id']) && $_GET['product_id'] == 3) { // Remove keyboard (product id 3)
    unset($_SESSION['cart'][2]);
    $session['cart_total'] -= $products[3]['price'];
  }

}



// --- Display Cart Contents ---

echo '<div style="border: 1px solid #ccc; padding: 10px; margin: 10px;">';
echo '<h2>Shopping Cart</h2>';

if (empty($_SESSION['cart'])) {
  echo '<p>Your cart is empty.</p>';
} else {
  echo '<ul>';
  foreach ($_SESSION['cart'] as $item) {
    echo '<li>' . $item['name'] . ' - $' . $item['price'] . ' x ' . $item['quantity'] . ' = $' . $item['price'] * $item['quantity'] . '</li>';
  }
  echo '</ul>';
  echo '<p><strong>Total:</strong> $' . number_format($session['cart_total'], 2) . '</p>';
}

echo '</div>';


?>
