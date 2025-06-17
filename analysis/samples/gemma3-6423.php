
<!DOCTYPE html>
<html>
<head>
  <title>Shopping Cart</title>
</head>
<body>

  <h1>Products</h1>
  <form method="post">
    <label for="product_1">Product 1 - Quantity: <input type="number" name="quantity" value="1"></label><br>
    <input type="hidden" name="product_id" value="1">
    <input type="submit" name="add_to_cart" value="Add to Cart">
  </form>

  <form method="post">
    <label for="product_2">Product 2 - Quantity: <input type="number" name="quantity" value="1"></label><br>
    <input type="hidden" name="product_id" value="2">
    <input type="submit" name="add_to_cart" value="Add to Cart">
  </form>

  <h2>Cart Total:</h2>
  <p>Calculated total will be displayed here (implementation required)</p>

</body>
</html>


<?php
session_start();

// Initialize the cart if it doesn't exist
if (!isset($_SESSION['cart'])) {
  $_SESSION['cart'] = array();
}

// --- Cart Functions ---

// Add an item to the cart
function add_to_cart($product_id, $product_name, $price, $quantity = 1) {
  if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
  }

  // Check if the product is already in the cart
  if (isset($_SESSION['cart'][$product_id])) {
    // If it exists, increment the quantity
    $_SESSION['cart'][$product_id]['quantity'] += $quantity;
  } else {
    // Otherwise, add a new item to the cart
    $_SESSION['cart'][$product_id] = array(
      'name' => $product_name,
      'price' => $price,
      'quantity' => $quantity
    );
  }
}

// Update the quantity of an item in the cart
function update_cart_quantity($product_id, $quantity) {
  if (isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id]['quantity'] = $quantity;
  } else {
    // If the product isn't in the cart, you could handle it by adding it with the given quantity
    // Alternatively, you might want to log an error or handle it differently.
    error_log("Product ID $product_id not found in cart and quantity not updated.");
  }
}


// Remove an item from the cart
function remove_from_cart($product_id) {
  if (isset($_SESSION['cart'][$product_id])) {
    unset($_SESSION['cart'][$product_id]);
  }
}

// Get the contents of the cart
function get_cart_contents() {
  return $_SESSION['cart'];
}

// Calculate the total cart value
function calculate_total() {
  $total = 0;
  if (isset($_SESSION['cart'])) {
    foreach($_SESSION['cart'] as $item) {
      $total += $item['price'] * $item['quantity'];
    }
  }
  return $total;
}

// --- Example Usage (Demonstration) ---

// Add some items to the cart
add_to_cart(1, 'Laptop', 1200, 1);
add_to_cart(2, 'Mouse', 25, 2);
add_to_cart(1, 'Laptop', 1200, 1); // Add another laptop
// Update quantity of mouse
update_cart_quantity(2, 5);

// Get the cart contents
$cart_items = get_cart_contents();
print_r($cart_items);

// Calculate the total
$total = calculate_total();
echo "Total Cart Value: $" . $total . "<br>";


// Remove an item from the cart
remove_from_cart(2);
echo "Cart after removing mouse: ";
print_r($cart_items);
?>
