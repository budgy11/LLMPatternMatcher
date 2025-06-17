    

4.  **Create a Link to `view_cart.php`:** Add a link on your main page (e.g., a button that says "View Cart") that points to `view_cart.php`.  You'll need to pass the product ID to `view_cart.php` so that the correct product information is displayed.

**Example Main Page (index.php):**



<?php
session_start();

// Database connection details (replace with your actual credentials)
$db_host = 'localhost';
$db_user = 'your_username';
$db_password = 'your_password';
$db_name = 'your_database_name';

// Define cart items
$cart = [];

// Function to add item to cart
function addToCart($product_id, $product_name, $price, $quantity) {
    global $cart;

    // Check if the product is already in the cart
    foreach ($cart as &$item) {
        if ($item['product_id'] == $product_id) {
            $item['quantity'] += $quantity;
            return; // Exit function if product already exists
        }
    }

    // If product not in cart, add it
    $cart[] = [
        'product_id' => $product_id,
        'product_name' => $product_name,
        'price' => $price,
        'quantity' => $quantity
    ];
}

// Function to update cart item quantity
function updateCartQuantity($product_id, $new_quantity) {
  global $cart;

  foreach ($cart as &$item) {
    if ($item['product_id'] == $product_id) {
      $item['quantity'] = $new_quantity;
      return;
    }
  }
  // Product not found, you might want to handle this differently
  // e.g., display an error message
  echo "Product ID " . $product_id . " not found in cart.";
}


// Function to remove item from cart
function removeCartItem($product_id) {
    global $cart;

    // Iterate through the cart and remove the item
    $new_cart = [];
    foreach ($cart as $item) {
        if ($item['product_id'] != $product_id) {
            $new_cart[] = $item;
        }
    }
    $cart = $new_cart; // Update the cart array
}

// Function to get cart contents
function getCartContents() {
    return $cart;
}

// Function to calculate total cart value
function calculateTotal() {
    $total = 0;
    foreach ($cart as $item) {
        $total += $item['price'] * $item['quantity'];
    }
    return $total;
}


// ---  Example Usage (Simulated Product Data - Replace with your database query) ---
$products = [
    1 => ['product_id' => 1, 'product_name' => 'Laptop', 'price' => 1200],
    2 => ['product_id' => 2, 'product_name' => 'Mouse', 'price' => 25],
    3 => ['product_id' => 3, 'product_name' => 'Keyboard', 'price' => 75]
];

// ---  Handle Add to Cart Request (Simulated) ---
if (isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];
    $product_name = $_POST['product_name'];
    $price = $_POST['price'];
    $quantity = $_POST['quantity'];

    addToCart($product_id, $product_name, $price, $quantity);
    // Redirect to the cart page
    header("Location: cart.php");
    exit();
}

//--- Handle Remove Cart Item Request (Simulated)
if (isset($_POST['remove_item'])) {
    $product_id = $_POST['product_id'];
    removeCartItem($product_id);
    header("Location: cart.php");
    exit();
}

//--- Handle Update Quantity Request (Simulated)
if (isset($_POST['update_quantity'])) {
    $product_id = $_POST['product_id'];
    $new_quantity = $_POST['quantity'];
    updateCartQuantity($product_id, $new_quantity);
    header("Location: cart.php");
    exit();
}

// --- Display Cart Contents (cart.php) ---
// (This is a basic example, you'll need to implement more complex cart display logic)
if (count($cart) > 0) {
    echo "<h2>Your Shopping Cart</h2>";
    echo "<ul>";
    foreach ($cart as $item) {
        echo "<li>" . $item['product_name'] . " - $" . $item['price'] . " x " . $item['quantity'] . " = $" . $item['price'] * $item['quantity'] . "</li>";
    }
    echo "</ul>";
    echo "<p><strong>Total: $" . calculateTotal() . "</strong></p>";
    echo "<form method='post' action='cart.php'>
                <input type='hidden' name='product_id' value='" . implode( "','" , array_map(function($item) {return $item['product_id'];}, $cart)) . "'>
                <input type='submit' value='Remove Item'>
              </form>";

} else {
    echo "<p>Your cart is empty.</p>";
}


?>
