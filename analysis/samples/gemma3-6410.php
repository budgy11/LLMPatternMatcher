

<?php
session_start();

// Assuming you have a shopping cart array stored in the session
// This is a simplified example; you'd likely have a more complex structure

$_SESSION['cart'] = array(); // Initialize the cart

// Example functions to add items to the cart
function addToCart($product_id, $product_name, $price, $quantity = 1) {
  // Check if the product is already in the cart
  if (isset($_SESSION['cart'][$product_id])) {
    // Product exists, increment the quantity
    $_SESSION['cart'][$product_id]['quantity'] += $quantity;
  } else {
    // Product doesn't exist, add it to the cart
    $_SESSION['cart'][$product_id] = array(
      'name' => $product_name,
      'price' => $price,
      'quantity' => $quantity
    );
  }
}


// Example function to remove an item from the cart
function removeFromCart($product_id) {
  if (isset($_SESSION['cart'][$product_id])) {
    unset($_SESSION['cart'][$product_id]);
  }
}

// Example function to update the quantity of an item in the cart
function updateQuantity($product_id, $quantity) {
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] = $quantity;
    }
}

// ---  Simulated user interaction (Replace with actual form handling) ---
// Example: User adds a product to the cart
if (isset($_POST['add_to_cart'])) {
  $product_id = $_POST['product_id'];
  $product_name = $_POST['product_name'];
  $price = $_POST['price'];
  $quantity = isset($_POST['quantity']) ? $_POST['quantity'] : 1;

  addToCart($product_id, $product_name, $price, $quantity);
}

// Example: User removes an item from the cart
if (isset($_POST['remove_from_cart'])) {
  $product_id = $_POST['product_id'];
  removeFromCart($product_id);
}

// Example: User updates the quantity of an item in the cart
if (isset($_POST['update_quantity'])) {
  $product_id = $_POST['product_id'];
  $new_quantity = $_POST['quantity'];
  updateQuantity($product_id, $new_quantity);
}


// --- Display the cart contents ---
echo "<div style='border: 1px solid #ccc; padding: 10px; margin: 10px;'>";
echo "<h2>Shopping Cart</h2>";

if (empty($_SESSION['cart'])) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($_SESSION['cart'] as $product_id => $product_details) {
    echo "<li>";
    echo "<strong>Product:</strong> " . $product_details['name'] . "<br>";
    echo "<strong>Price:</strong> $" . $product_details['price'] . "<br>";
    echo "<strong>Quantity:</strong> " . $product_details['quantity'] . "<br>";
    echo "</li>";
  }
  echo "</ul>";
}

echo "</div>";

// Example form for adding an item to the cart (simple)
echo "<br>";
echo "<form method='post' action=''>";
echo "<label for='product_id'>Product ID:</label><input type='text' id='product_id' name='product_id' value='1'><br>"; // Example product ID
echo "<label for='product_name'>Product Name:</label><input type='text' id='product_name' name='product_name' value='Example Product'><br>";
echo "<label for='price'>Price:</label><input type='number' id='price' name='price' value='19.99'><br>";
echo "<label for='quantity'>Quantity:</label><input type='number' id='quantity' name='quantity' value='1'><br>";
echo "<input type='submit' name='add_to_cart' value='Add to Cart'>";
echo "</form>";


?>
